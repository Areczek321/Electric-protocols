<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Protokół z pomiarów ochronnych</title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        body {
            margin: 0;
            background: #e5e7eb;
            color: #111827;
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .break-all {
            word-break: break-all;
            overflow-wrap: break-word;
        }

        .page {
            width: 210mm;
            margin: 10mm auto;
            padding: 8mm;
            background: white;
        }

        .protocol-info {
            font-size: 13px;
        }

        h1 {
            margin: 12mm 0 6mm;
            border: 1px solid #555;
            padding: 3mm;
            text-align: center;
            font-family: "Times New Roman", serif;
            font-size: 30px;
        }

        .protocol-number {
            margin-bottom: 6mm;
            text-align: center;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        th,
        td {
            border: 1px solid #555;
            padding: 1.2mm;
            vertical-align: middle;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        th {
            font-weight: 600;
            text-align: center;
            background-color: #808080 !important;
        }

        .label {
            color: #374151;
            font-size: 13px;
        }

        .value-space {
            min-height: 5mm;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 10mm;
            min-height: 7mm;
            justify-content: center;
            font-size: 14px;
        }

        .checkbox {
            position: relative;
            display: inline-block;
            width: 4mm;
            height: 4mm;
            margin-right: 1mm;
            border: 1px solid #444;
            vertical-align: middle;
        }

        .checkbox.checked::after {
            content: "✓";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -58%);
            font-size: 20px;
            font-weight: 700;
            line-height: 1;
        }

        .judgement {
            vertical-align: top;
        }

        .judgement-box {
            min-height: 50mm;
        }

        .table-title {
            border: none !important;
            /* padding: 1.5mm; */
            padding-top: 1.5mm;
            padding-left: 1.5mm;
            padding-right: 1.5mm;
            text-align: center;
            break-after: avoid;
            font-size: 14px;
        }

        .table-parameters {
            border: 1px solid #555 !important;
            padding: 1.5mm;
            text-align: center;
            break-after: avoid;
            font-size: 11px;
            background-color: #fff;
        }

        .full-page-attachment {
            max-width: 100%;
            max-height: 275mm;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .section {
            background: #9eb8cb;
            color: #111827;
            font-size: 15px;
            font-weight: 700;
            text-align: left;
        }

        .description-row td {
            border-top: 0;
            background: #f8fafc;
            font-size: 11px;
        }

        .small-table {
            font-size: 11px;
        }

        .small-table th,
        .small-table td {
            padding: 0.8mm 0.5mm;
            height: 5.5mm;
        }

        .very-small {
            font-size: 8px;
        }

        .empty-row td {
            height: 6mm;
        }

        .empty-row-large td {
            height: 7mm;
        }

        .mt-2 {
            margin-top: 2mm;
        }

        .mt-4 {
            margin-top: 4mm;
        }

        .avoid-break {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .no-print {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 10;
            border: 0;
            border-radius: 6px;
            background: #2563eb;
            padding: 10px 18px;
            color: white;
            cursor: pointer;
        }

        @media print {
            body {
                background: white;
            }

            .page {
                width: auto;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .section {
                background-color: #9eb8cb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            td,
            th {
                border-bottom: 1px solid #555 !important;
                border-top: 1px solid #555 !important;
            }

            thead {
                display: table-header-group;
                /* Powtarza nagłówek tabeli na każdej stronie */
            }

            tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }

        .page-break {
            page-break-before: always;
            break-before: page;
        }

        .grade-cell {
            text-align: center;
            vertical-align: middle;
            padding: 2px 4px;
        }

        .badge {
            display: inline-block;
            width: 100%;
            text-align: center;
            padding: 3px 6px;
            font-size: 8px;
            font-weight: 700;
            border-radius: 4px;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #047857;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .badge-gray {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .print-table-header {
            text-align: left !important;
            font-weight: bold !important;
            font-size: 15px !important;
            padding: 2mm 0 !important;
            border: none !important;
            background: transparent !important;
        }
    </style>
</head>

<body>
    {{ $slot }}
</body>

</html>
