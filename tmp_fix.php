<?php
$file = 'resources/views/admin/api/index.blade.php';
$content = file_get_contents($file);

$pattern = '/@click="async \(\) => \{[\s\S]*?const url = document\.getElementById\(\'webhook_url\'\)\.value;[\s\S]*?const secret = document\.getElementById\(\'webhook_secret\'\)\.value;[\s\S]*?const response = await fetch\(\'\{\{ route\(\'admin\.settings\.update\'\) \}\}\', \{[\s\S]*?method: \'PUT\',[\s\S]*?headers: \{ \'Content-Type\': \'application\/json\', \'X-CSRF-TOKEN\': \'\{\{ csrf_token\(\) \}\}\' \},[\s\S]*?body: JSON\.stringify\(\{ settings: \{ webhook_url: url, webhook_secret: secret \} \}\)[\s\S]*?\}\);[\s\S]*?if\(response\.ok\) window\.dispatchEvent\(new CustomEvent\(\'toast\', \{ detail: \{ message: \'Webhook configuration synchronized!\', type: \'success\' \} \}\)\);[\s\S]*?else window\.dispatchEvent\(new CustomEvent\(\'toast\', \{ detail: \{ message: \'Failed to save webhook settings\.\', type: \'error\' \} \}\)\);[\s\S]*?\}"/m';

$replacement = '@click="async () => {
    const url = document.getElementById(\'webhook_url\').value;
    const secret = document.getElementById(\'webhook_secret\').value;
    const selectedEvents = Array.from(document.querySelectorAll(\'input[name=\\\'webhook_events[]\\\']:checked\')).map(cb => cb.value);
    const response = await fetch(\'{{ route(\\\'admin.settings.update\\\') }}\', {
        method: \'PUT\',
        headers: { \'Content-Type\': \'application/json\', \'X-CSRF-TOKEN\': \'{{ csrf_token() }}\' },
        body: JSON.stringify({ settings: { webhook_url: url, webhook_secret: secret, webhook_events: JSON.stringify(selectedEvents) } })
    });
    if(response.ok) window.dispatchEvent(new CustomEvent(\'toast\', { detail: { message: \'Webhook configuration synchronized!\', type: \'success\' } }));
    else window.dispatchEvent(new CustomEvent(\'toast\', { detail: { message: \'Failed to save webhook settings.\', type: \'error\' } }));
}"';

$newContent = preg_replace($pattern, $replacement, $content);
file_put_contents($file, $newContent);
echo "Replaced: " . ($content !== $newContent ? "Yes" : "No");
