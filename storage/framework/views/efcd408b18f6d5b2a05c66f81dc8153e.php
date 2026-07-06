<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice <?php echo e($invoice->invoice_number); ?></title>
    <?php echo $__env->make('invoices.partials.document-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="pdf-body">
    <?php echo $__env->make('invoices.partials.document', ['editable' => false, 'forPdf' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/invoices/pdf.blade.php ENDPATH**/ ?>