<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    @include('invoices.partials.document-styles')
</head>
<body class="pdf-body">
    @include('invoices.partials.document', ['editable' => false, 'forPdf' => true])
</body>
</html>
