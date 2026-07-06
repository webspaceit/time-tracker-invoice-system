<style>

    /* ── Web preview ─────────────────────────────────────────── */
    .invoice-doc {
        font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #222;
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
    }
    .invoice-doc .doc-title {
        font-size: 28px;
        font-weight: bold;
        letter-spacing: 2px;
        margin: 0 0 20px;
    }
    .invoice-doc .header-grid {
        width: 100%;
        border-collapse: collapse;
    }
    .invoice-doc .header-grid td {
        vertical-align: top;
        padding: 0;
    }
    .invoice-doc .issuer-name {
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .invoice-doc .company-address {
        font-size: 10px;
        margin-top: 6px;
        max-width: 280px;
    }
    .invoice-doc .bill-to-name {
        font-weight: bold;
        font-size: 15px;
        font-style: italic;
    }
    .invoice-doc .customer-email {
        font-size: 13px;
    }
    .invoice-doc .label {
        font-weight: bold;
        font-size: 13px;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .invoice-doc .bill-to-label {
        font-weight: bold;
        font-size: 20px;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        text-transform: uppercase;
    }
    .invoice-doc .meta-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1%;
    }
    .invoice-doc .meta-table td {
        padding: 2px 0;
        text-align: right;
    }
    .invoice-doc .items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 5% 0 5% 0;
        border: 1px solid #ccc;
    }
    .invoice-doc .items-table th {
        border-bottom: 2px solid #333;
        padding: 1px 3px;
        text-align: left;
        font-size: 13px;
        text-transform: uppercase;
    }
    .invoice-doc .items-table th .duration-hint {
        font-size: 12px;
        font-weight: bold;
        text-transform: none;
        text-align: center !important;
    }
    .invoice-doc .items-table th.amount,
    .invoice-doc .items-table td.amount {
        text-align: right;
        width: 120px;
    }
    .invoice-doc .items-table td.amount {
        font-size: 15px;
    }
    .invoice-doc .items-table th.duration,
    .invoice-doc .items-table td.duration {
        text-align: center;
        width: 110px;
    }
    .invoice-doc .items-table td {
        padding: 5px 5px;
        border-bottom: 1px solid #ddd;
        vertical-align: top;
        font-size: 13px;
    }
    .invoice-doc .total-row td {
        font-weight: bold;
        border-bottom: 2px solid #333;
        padding-top: 12px;
    }
    .invoice-doc .signature-block {
        margin-top: 5%;
        margin-bottom: 2%;
    }
    .invoice-doc .invoice-signature {
        max-height: 65px;
        max-width: 220px;
        height: auto;
        display: block;
    }
    .invoice-doc .invoice-divider {
        border: none;
        border-top: 1px solid #333;
        margin: 5px 0 5px;
        width: 20%;
    }
    .invoice-doc .thank-you {
        margin: 0 0 8px;
        font-weight: bold;
        font-size: 15px;
        font-family: cursive;
    }
    .invoice-doc .terms-title {
        font-weight: bold;
        margin-bottom: 6px;
        font-size: 14px;
        line-height: 25px;
        text-decoration: underline;
    }
    .invoice-doc .terms-list {
        margin: 0 0 8px;
        padding-left: 0;
        list-style: none;
    }
    .invoice-doc .terms-list li {
        margin-bottom: 4px;
        font-size: 13px;
    }
    .invoice-doc .work-details {
        margin: 5% 0 5% 0;
    }
    .invoice-doc .work-details-title {
        font-weight: bold;
        font-size: 15px;
        line-height: 20px;
        margin-bottom: 4px;
    }
    .invoice-doc .work-details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 14px;
        border: 1px solid #ccc;
    }
    .invoice-doc .work-details-table thead tr {
        background: #333;
        color: #fff;
    }
    .invoice-doc .work-details-table th {
        padding: 5px 8px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        text-align: left;
        border: none;
    }
    .invoice-doc .work-details-table th.wd-title {
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        padding: 6px 8px;
        border-bottom: 1px solid #555;
    }
    .invoice-doc .work-details-table th.wd-num {
        width: 32px;
        text-align: center;
    }
    .invoice-doc .work-details-table th.wd-sep {
        width: 1px;
        padding: 0;
        background: #e2e8f0;
    }
    .invoice-doc .work-details-table td {
        padding: 8px 12px;
        font-size: 13px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
        width: 45%;
        line-height: 1.65;
    }

    .invoice-doc .work-details-table td.wd-num {
        text-align: center;
        width: 24px;
        font-weight: bold;
        color: #718096;
        border-right: 1px solid #e2e8f0;
    }
    .invoice-doc .work-details-table td.wd-sep {
        width: 1px;
        padding: 0;
        background: #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }
    .invoice-doc .work-details-table .wd-even { background: #fff; }
    .invoice-doc .work-details-table .wd-odd  { background: #f8fafc; }
    .invoice-doc .bank-block {
        margin-top: 4%;
        padding: 10px;
        background-color: #f7f8fa;
        border-radius: 4px;
        font-size: 12px;
    }
    .invoice-doc .bank-block-title {
        font-weight: bold;
        font-size: 14px;
        color: #1a1a1a;
        margin: 0 0 10px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    .invoice-doc .bank-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }
    .invoice-doc .bank-table td {
        border-bottom: 1px solid #e2e8f0;
        padding: 8px 10px;
        vertical-align: middle;
    }
    .invoice-doc .bank-table tr:last-child td {
        border-bottom: none;
    }
    .invoice-doc .bank-label {
        width: 30%;
        font-weight: bold;
        font-size: 13px;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .invoice-doc .bank-value {
        width: 70%;
        font-size: 12px;
        color: #222;
        line-height: 1.45;
    }
    .invoice-doc .form-control-inline {
        border: 1px dashed #aaa;
        padding: 4px 8px;
        width: 100%;
        font-size: 12px;
        background: #fafafa;
    }
    .invoice-doc select.form-control-inline { height: 32px; }
    .invoice-doc textarea.form-control-inline { min-height: 80px; resize: vertical; }
    .invoice-doc .item-input-row td { padding: 6px 4px; }
    .invoice-doc .btn-remove-item { font-size: 13px; padding: 2px 8px; }

    /* ── A4 PDF — compact single-page ───────────────────────── */
    @page {
        size: A4;
        margin: 2.48cm 2.54cm;
    }
    body.pdf-body {
        margin: 0;
        padding: 0;
        font-size: 11px;
        line-height: 15px;
    }
    .invoice-doc.invoice-doc-pdf {
        max-width: 100%;
        font-size: 11px;
        line-height: 15px;
    }
    .invoice-doc-pdf .doc-title {
        font-size: 22px;
        line-height: 24px;
        margin: 0 0 8px;
    }
    .invoice-doc-pdf .issuer-name {
        font-size: 12px;
        line-height: 16px;
    }
    .invoice-doc-pdf .company-address {
        font-size: 10px;
        line-height: 14px;
        margin-top: 4px;
    }
    .invoice-doc-pdf .bill-to-name {
        font-weight: bold;
        font-size: 14px;
        font-style: italic;
    }
    .invoice-doc-pdf .customer-email {
        font-size: 10px;
        line-height: 14px;
    }
    .invoice-doc-pdf .items-table th .duration-hint {
        font-size: 9px;
        font-weight: normal;
    }
    .invoice-doc-pdf .header-grid { margin-bottom: 8px; }
    .invoice-doc-pdf .label {
        font-weight: bold;
        font-size: 10px;
        line-height: 14px;
        margin-bottom: 2px;
    }
    .invoice-doc-pdf .bill-to-label {
        font-weight: bold;
        font-size: 14px;
        line-height: 18px;
        margin-bottom: 2px;
        text-transform: uppercase;
    }
    .invoice-doc-pdf td.inv { padding-top: 4px; }
    .invoice-doc-pdf .meta-table td {
        font-size: 11px;
        padding: 1px 0;
    }
    .invoice-doc-pdf .items-table { margin: 5% 0 5% 0; }
    .invoice-doc-pdf .items-table th {
        border-bottom: 2px solid #333;
        padding: 2px 4px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
    }
    .invoice-doc-pdf .items-table td {
        padding: 3px 4px;
        border-bottom: 1px solid #ddd;
        vertical-align: top;
        font-size: 12px;
    }
    .invoice-doc-pdf .total-row td {
        padding-top: 3px;
        font-size: 12px;
    }
    .invoice-doc-pdf .signature-block {
        margin-top: 5%;
        margin-bottom: 2%;
    }
    .invoice-doc-pdf .invoice-signature {
        max-height: 46px;
        max-width: 160px;
    }
    .invoice-doc-pdf .invoice-divider { margin: 4px 0; }
    .invoice-doc-pdf .thank-you {
        font-size: 14px;
        line-height: 18px;
        margin: 0 0 4px;
    }
    .invoice-doc-pdf .terms-title {
        font-size: 11px;
        line-height: 16px;
        margin-top: 2px;
        margin-bottom: 2px;
    }
    .invoice-doc-pdf .terms-list {
        margin-bottom: 4px;
        font-size: 10px;
        line-height: 14px;
    }
    .invoice-doc-pdf .terms-list li {
        margin-bottom: 2px;
        line-height: 14px;
    }
    .invoice-doc-pdf .work-details { margin: 5% 0 5% 0; }
    .invoice-doc-pdf .work-details-title {
        font-weight: bold;
        font-size: 12px;
        line-height: 14px;
        margin: 0;
        padding: 0;
        display: block;
    }
    .invoice-doc-pdf .work-details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
        border: 1px solid #bbb;
    }
    .invoice-doc-pdf .work-details-table thead tr {
        background: #333;
        color: #fff;
    }
    .invoice-doc-pdf .work-details-table th {
        padding: 3px 6px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        text-align: left;
        border: none;
    }
    .invoice-doc-pdf .work-details-table th.wd-title {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        padding: 4px 6px;
        border-bottom: 1px solid #555;
    }
    .invoice-doc-pdf .work-details-table th.wd-num {
        width: 14px;
        text-align: center;
    }
    .invoice-doc-pdf .work-details-table th.wd-sep {
        width: 1px;
        padding: 0;
        background: #e2e8f0;
    }
    .invoice-doc-pdf .work-details-table td {
        padding: 5px 8px;
        font-size: 11px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
        line-height: 1.55;
    }

    .invoice-doc-pdf .work-details-table td.wd-num {
        text-align: center;
        width: 24px;
        font-weight: bold;
        color: #718096;
        border-right: 1px solid #e2e8f0;
    }
    .invoice-doc-pdf .work-details-table td.wd-sep {
        width: 1px;
        padding: 0;
        background: #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }
    .invoice-doc-pdf .work-details-table .wd-even { background: #fff; }
    .invoice-doc-pdf .work-details-table .wd-odd  { background: #f8fafc; }
    .invoice-doc-pdf .bank-block {
        margin-top: 4%;
        padding: 5px 8px;
        background-color: #f7f8fa;
        border-radius: 3px;
        font-size: 10px;
        page-break-inside: avoid;
    }
    .invoice-doc-pdf .bank-block-title {
        font-size: 11px;
        padding-bottom: 3px;
    }
    .invoice-doc-pdf .bank-table td {
        border-bottom: 1px solid #e2e8f0;
        padding: 5px 6px;
        font-size: 10px;
        vertical-align: middle;
    }
    .invoice-doc-pdf .bank-table tr:last-child td {
        border-bottom: none;
    }
    .invoice-doc-pdf .bank-label {
        font-size: 10px;
        width: 30%;
        font-weight: bold;
    }
    .invoice-doc-pdf .bank-value { 
        font-size: 10px; 
        width: 70%;
    }
    .invoice-doc-pdf .terms-section,
    .invoice-doc-pdf .signature-block,
    .invoice-doc-pdf .items-table {
        page-break-inside: avoid;
    }
</style>
