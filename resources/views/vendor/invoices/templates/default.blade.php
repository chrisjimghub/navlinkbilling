<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $invoice->name }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style type="text/css" media="screen">
            html {
                font-family: sans-serif;
                line-height: 1.15;
                margin: 0;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: #fff;
                font-size: 10px;
                margin: 36pt;
            }

            h4 {
                margin-top: 0;
                margin-bottom: 0.5rem;
            }

            p {
                margin-top: 0;
                margin-bottom: 1rem;
            }

            strong {
                font-weight: bolder;
            }

            img {
                vertical-align: middle;
                border-style: none;
            }

            table {
                border-collapse: collapse;
            }

            th {
                text-align: inherit;
            }

            h4, .h4 {
                margin-bottom: 0.5rem;
                font-weight: 500;
                line-height: 1.2;
            }

            h4, .h4 {
                font-size: 1.5rem;
            }

            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
            }

            .table th,
            .table td {
                padding: 0.75rem;
                vertical-align: top;
            }

            .table.table-items td {
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .mt-5 {
                margin-top: 3rem !important;
            }

            .pr-0,
            .px-0 {
                padding-right: 0 !important;
            }

            .pl-0,
            .px-0 {
                padding-left: 0 !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-center {
                text-align: center !important;
            }

            .text-uppercase {
                text-transform: uppercase !important;
            }
            * {
                font-family: "DejaVu Sans";
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                line-height: 1.1;
            }
            .party-header {
                font-size: 1.5rem;
                font-weight: 400;
            }
            .total-amount {
                font-size: 12px;
                font-weight: 700;
            }
            .border-0 {
                border: none !important;
            }
            .cool-gray {
                color: #6B7280;
            }

            .cool-green {
                color: #10B981;
            }
        </style>
    </head>

    <body>
        @if($invoice->logo)
            {{-- <img src="{{ $invoice->getLogo() }}" alt="logo" height="100"> --}}
        @endif

        {{-- Seller - Buyer --}}
        <table class="table">
            <thead>
                <tr>
                    <th class="border-0 pl-0 party-header" width="60%">
                        {{ __('invoices::invoice.buyer') }}
                    </th>
                    <th class="border-0" width="3%"></th>
                    <th class="border-0 pl-0 party-header">
                        <h4 class="text-uppercase {{ strtolower($invoice->status) == 'paid' ? 'cool-green' : 'cool-gray' }}">
                            <strong>{{ $invoice->status }}</strong>
                        </h4>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
                        @foreach($invoice->buyer->custom_fields as $key => $value)
                            <p class="seller-custom-field">
                                {{ ucfirst($key) }}: {{ $value }}
                            </p>
                        @endforeach
                    </td>
                    <td class="border-0"></td>
                    <td class="px-0">
                        <p>{{ __('invoices::invoice.date') }}: <strong>{{ $invoice->getDate() }}</strong></p>
                        <p>{{ __('Billing ID') }}: <strong>{{ $invoice->getSerialNumber() }}</strong></p>
                        <p>{{ __('Billing Period') }}: <strong>{{ $invoice->getCustomData()['billing_period'] }}</strong></p>
                        <p>{{ __('Cut Off Date') }}: <strong>{{ $invoice->getCustomData()['date_cut_off'] }}</strong></p>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Table --}}
        <table class="table table-items">
            <thead>
                <tr>
                    <th scope="col" class="border-0 pl-0">{{ __('invoices::invoice.description') }}</th>
                    <th scope="col" class="text-center border-0"></th>
                    <th scope="col" class="text-right border-0"></th>
                    @if($invoice->hasItemDiscount)
                        <th scope="col" class="text-right border-0">{{ __('Deduction') }}</th>
                    @endif
                    <th scope="col" class="text-right border-0 pr-0">{{ __('Amount') }}</th>
                    
                </tr>
            </thead>
            <tbody>
                {{-- Items --}}
                @foreach($invoice->items as $item)
                <tr>
                    <td class="pl-0">
                        {{ $item->title }}

                        @if($item->description)
                            <p class="cool-gray">{{ $item->description }}</p>
                        @endif
                    </td>
                    <td class="text-center"></td>
                    <td class="text-right"></td>
                    
                    @if($invoice->hasItemDiscount)
                        <td class="text-right" style="color: red;">
                            @if($item->discount != 0) 
                                {{ $invoice->formatCurrency($item->discount) }}
                            @endif
                        </td>
                    @endif

                    <td class="text-right pr-0" style="color: green">
                        @if($item->price_per_unit != 0)
                            {{ $invoice->formatCurrency($item->price_per_unit) }}
                        @endif
                    </td>

                </tr>
                @endforeach

                {{-- Summary --}}

                {{-- Total Deduction(discount) --}}
                @if($invoice->hasItemOrInvoiceDiscount())
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="text-right pl-0">{{ __('Total Deduction') }}</td>
                        <td class="text-right pr-0" style="color:red">
                                {{ $invoice->formatCurrency($invoice->total_discount) }}
                        </td>
                    </tr>
                @endif
                    
                {{-- Total Amount --}}
                <tr>
                    <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                    <td class="text-right pl-0">{{ __('Total Amount') }}</td>
                    <td class="text-right pr-0 total-amount">
                        {{ $invoice->formatCurrency($invoice->total_amount) }}
                    </td>
                </tr>

            </tbody>
        </table>

        
        @if($invoice->notes)
            <p>
                {{ __('invoices::invoice.notes') }}: {!! $invoice->notes !!}
            </p>
        @endif

        <p>
            {{ __('invoices::invoice.amount_in_words') }}: {{ $invoice->getTotalAmountInWords() }}
        </p>
        <p>
            {{ __('invoices::invoice.pay_until') }}: {{ $invoice->getPayUntilDate() }}
        </p>

        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "{{ __('invoices::invoice.page') }} {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
