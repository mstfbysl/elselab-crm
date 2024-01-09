<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <style>
            body {
                font-family: DejaVu Sans;
                font-size: 12px;
            }
        
            #items {
                border-collapse: collapse;
                width: 100%;
            }
            #items td{
                border: none;
                border-bottom: 1px solid black;
                padding: 8px;
                font-size: 12px;
            }
                
            #items th {
                border-bottom: 2px solid black;
                padding-top: 8px;
                padding-bottom: 8px;
                text-align: left;
                color: black;
            }
            .right{
                float: right;
            }

            h2{
                text-align:left;
                margin-top:0px !important;
                margin-bottom:0px !important;
                font-weight: regular;
            }

            h3{
                margin-top:0px !important;
                margin-bottom:0px !important;
                font-weight: regular;
            }

            #footer{
                position: fixed;
                width: 100%;
                bottom: 25px;
                left: 0;
                right: 0;
                border-top: 1px solid #000; 
            }
        </style>
    </head>
    <body>
        <div style="margin: auto; width: 100%">
            <table border="0" style="width:100%;">
                <tr>
                    <td>
                        @if ($logo)
                            <img src="data:image/svg+xml;base64,{{ $logo }}" height="75">
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <h2 style="font-weight: regular; text-align:right;">{{ __('pdf.Proforma') }}</h2>
                        <h2 style="font-weight: regular; text-align:right;">{{ __('pdf.Serie') }} <strong>{{ $serie }}</strong> Nr. <strong>{{ $no }}</strong></h2>
                        <h3 style="font-weight: regular;">{{ __('pdf.Issued') }}: {{ $date }}</h3>
                    </td>
                </tr>

            </table>
            <br/>
            <table border="0" style="width:100%; margin-top: 1em;">
                <tr style="height:50px">
                    <td style="width: 50%"><h3>{{ __('pdf.Billed to') }}</h3></th>
                    <td style="width: 50%"><h3>{{ __('pdf.From') }}</h3></td>
                </tr>
                <tr style="vertical-align: text-top;">
                    <td>
                        <h3 style="font-weight: bold">{{ $client_company_name }}</h3>
                        {{ __('pdf.Company Code') }}: {{ $client_company_code }} <br>
                        {{ $client_company_address }} <br>
                        {{ __('pdf.VAT No') }}: {{ $client_company_vat_code }} <br>
                    </td>
                    <td>
                        <h3 style="font-weight: bold">{{ $from_company_name }}</h3>
                        {{ $from_company_code }} <br>
                        {{ $from_company_address }} <br>
                        {{ __('pdf.VAT No') }}: {{ $from_company_vat_no }}<br>
                        {{ session('company_bank_iban') }}<br>
                        {{ session('company_bank_code') }}
                    </td>
                </tr>
            </table>
        
            <table id="items" border="0" style="width:100%; margin-top: 2em; margin-bottom: 2em;">
                <tr>
                    <th>{{ __('pdf.No') }}</th>
                    <th>{{ __('pdf.Title') }}</th>
                    <th>{{ __('pdf.Quantity') }}</th>
                    <th>{{ __('pdf.Price per qty') }}</th>
                    <th>{{ __('pdf.Amount') }}</th>
                </tr>
                {{$j = 1}}
                @foreach ($items as $i)
                <tr>
                    <td>{{ $j }}</td>
                    <td>{{ $i["title"] }}</td>
                    <td>{{ $i["quantity"] }}</td>
                    <td>{{ (new \App\Helpers\Helpers)::human_format($i["cost"]) }}</td>
                    <td>{{ (new \App\Helpers\Helpers)::human_format($i["sub_total"]) }}</td>
                </tr>
                {{$j++}}
                @endforeach
            </table>
            
            <div id="invoice-totals" style="float:right; width: 30%; margin-top: 2em; margin-bottom: 2em;">
                <strong>{{ __('pdf.Subtotal') }}:</strong> <span class="right">{{ (new \App\Helpers\Helpers)::human_format($sub_total) }}</span> <br>
                <strong>{{ __('pdf.Tax') }}: </strong> <span class="right">{{ (new \App\Helpers\Helpers)::human_format($tax_total) }} </span><hr>
                <strong>{{ __('pdf.Total') }}: </strong> <span class="right">{{ (new \App\Helpers\Helpers)::human_format($total) }} </span><br>
            </div>

            <div style="clear:both"></div>
        
            <div id="invoice-footer">
                @if ($note)
                <strong>{{ __('pdf.Note') }}: </strong> {{ $note }} <br>
                @endif

                @if (app()->getLocale() == 'lt')
                <strong>{{ __('pdf.SuminText') }}: </strong> {{ $suma_text }} <br>
                @endif

                <div id="invoice-footer-signature" style="margin-top: 2em">
                    <table border="0" style="width:100%;">
                        <tr>
                            <td><strong>{{ __('pdf.Issued by') }}:</strong> {{ $user_name_surname }}</td>
                        </tr>
                    </table>
                </div>
            </div>        
        </div> 
        <table border="0" id="footer">
            <tr style="vertical-align: text-top;">
                <td>
                    {{ session('company_bank_name') }}<br>
                    {{ session('company_bank_iban') }}<br>
                    {{ session('company_bank_code') }}
                </td>
                <td>
                    {{ __('pdf.Tel') }}.: {{ session('company_phone') }}<br>
                    {{ __('pdf.Email') }}: {{ session('company_email') }}
                </td>
            </tr>
        </table>
    </body>
</html>
