<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - <?php echo e($transaction->reference); ?></title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #1e293b;
            line-height: 1.5;
            padding: 0;
            margin: 0;
            background: #f8fafc;
        }

        .invoice-wrapper {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .header-bg {
            background-color: #6366f1; /* Vibrant Indigo */
            background-image: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            color: #ffffff;
            padding: 40px 30px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .header-content {
            width: 100%;
        }

        .header-content td {
            vertical-align: middle;
        }

        .logo {
            max-width: 120px;
            max-height: 40px;
            background: #ffffff;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            text-align: right;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .details-box {
            background: #ffffff;
            margin: -20px 30px 20px 30px;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); /* Works in some PDF engines, ignored in others */
            border: 1px solid #e2e8f0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            vertical-align: top;
            width: 50%;
        }

        .heading-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .badge-success {
            background-color: #10b981;
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-pending {
            background-color: #f59e0b;
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-failed {
            background-color: #ef4444;
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
        }

        .items-table th {
            text-align: left;
            padding: 12px 15px;
            background-color: #f1f5f9;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #cbd5e1;
        }

        .items-table th.right, .items-table td.right {
            text-align: right;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
            font-size: 13px;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .total-row td {
            background-color: #f8fafc;
            font-weight: bold;
            font-size: 16px;
            color: #0f172a;
            border-top: 2px solid #6366f1;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-size: 11px;
        }

        .footer-logo {
            color: #cbd5e1;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="invoice-wrapper">
        <!-- Colorful Header -->
        <div class="header-bg">
            <table class="header-content">
                <tr>
                    <td>
                        <?php if(file_exists(public_path('images/logo.png'))): ?>
                            <img src="<?php echo e(public_path('images/logo.png')); ?>" class="logo" alt="CloudTech Logo">
                        <?php else: ?>
                            <h2 style="margin:0; font-size: 24px;">CLOUDTECH</h2>
                        <?php endif; ?>
                    </td>
                    <td>
                        <h1 class="invoice-title">RECEIPT</h1>
                        <p style="text-align: right; margin: 4px 0 0 0; font-size: 12px; opacity: 0.9;">
                            #<?php echo e($transaction->reference); ?>

                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Main Details Card -->
        <div class="details-box">
            <table class="info-table">
                <tr>
                    <td>
                        <div class="heading-label">Billed To</div>
                        <div class="info-value">
                            <?php echo e($transaction->user->name ?? 'User'); ?><br>
                            <span style="font-weight: normal; font-size: 12px; color: #475569;">
                                <?php echo e($transaction->user->email ?? ''); ?><br>
                                <?php echo e($transaction->user->phone ?? ''); ?>

                            </span>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div class="heading-label">Transaction Date</div>
                        <div class="info-value"><?php echo e($transaction->created_at->format('M d, Y')); ?></div>
                        
                        <div class="heading-label" style="margin-top: 15px;">Payment Status</div>
                        <div style="margin-top: 4px;">
                            <span class="badge-<?php echo e($transaction->status); ?>">
                                <?php echo e(ucfirst($transaction->status)); ?>

                            </span>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong><?php echo e($transaction->description ?? 'Data Bundle Purchase'); ?></strong><br>
                            <span style="font-size: 11px; color: #64748b;">Ref: <?php echo e($transaction->reference); ?></span>
                        </td>
                        <td class="right" style="vertical-align: top;">
                            GHS <?php echo e(number_format($transaction->amount, 2)); ?>

                        </td>
                    </tr>
                    <tr class="total-row">
                        <td style="text-align: right; padding-right: 20px;">Total Paid:</td>
                        <td class="right" style="color: #6366f1;">
                            GHS <?php echo e(number_format($transaction->amount, 2)); ?>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div class="footer-logo">
                <strong style="color: #64748b; font-size: 12px;">CloudTech Data Services</strong>
            </div>
            <p style="margin: 0;">Thank you for your business!</p>
            <p style="margin: 4px 0 0 0; opacity: 0.7;">This is an electronically generated receipt.</p>
        </div>
    </div>
</body>

</html><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views/invoices/transaction.blade.php ENDPATH**/ ?>