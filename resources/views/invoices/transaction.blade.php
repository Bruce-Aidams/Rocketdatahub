<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $transaction->reference }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.4;
            padding: 0;
            margin: 0;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            background: #fff;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 35px;
            line-height: 35px;
            color: #6366f1;
            font-weight: bold;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #f8f9fa;
            border-bottom: 2px solid #eee;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
            font-size: 18px;
            color: #6366f1;
        }

        .status-success {
            color: #065f46;
            background: #d1fae5;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
        }

        .status-pending {
            color: #92400e;
            background: #fef3c7;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
        }

        .status-failed {
            color: #991b1b;
            background: #fee2e2;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">CloudTech</td>
                            <td>
                                Invoice #: {{ $transaction->reference }}<br>
                                Date: {{ $transaction->created_at->format('M d, Y') }}<br>
                                Status: <span
                                    class="status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>Bill To:</strong><br>
                                {{ $transaction->user->name ?? 'User' }}<br>
                                {{ $transaction->user->email ?? '' }}<br>
                                {{ $transaction->user->phone ?? '' }}
                            </td>
                            <td>
                                <strong>Platform:</strong><br>
                                CloudTech Data Services<br>
                                Support: support@cloudtech.com
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Description</td>
                <td>Amount</td>
            </tr>

            <tr class="item">
                <td>
                    {{ $transaction->description ?? 'Data Bundle Purchase' }}<br>
                    <small style="color: #777;">Ref: {{ $transaction->reference }}</small>
                </td>
                <td>GHC {{ number_format($transaction->amount, 2) }}</td>
            </tr>

            <tr class="total">
                <td></td>
                <td>Total: GHC {{ number_format($transaction->amount, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Thank you for using CloudTech!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
        </div>
    </div>
</body>

</html>