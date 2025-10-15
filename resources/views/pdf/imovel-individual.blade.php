<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório Individual</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px;  }
        .logo { max-width: 100px; margin-bottom: 10px;  max-height: 80px; }
        .empresa-nome { font-size: 24px; font-weight: bold; margin: 10px 0; }
        .subtitulo { font-size: 14px; color: #666; margin-bottom: 20px; }
        .info-section { margin: 20px 0; }
        .info-title { font-weight: bold; font-size: 12px; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 12px; }
        .info-item { margin-bottom: 8px; }
        .info-label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
        .no-data { text-align: center; color: #666; font-style: italic; padding: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('storage/images/logo.png') }}" class="logo" alt="Logo">
        <div class="empresa-nome">Prefeitura Municipal de São Leopoldo</div>
        <div class="subtitulo">RELATÓRIO DETALHADO DE IMÓVEL</div>
    </div>

        <table class="info-table">
        <tr>
            <td class="info-label">Inscrição:</td>
            <td>{{ $imovel->id }}</td>
            <td class="info-label">Situação:</td>
            <td>{{ $imovel->situacao }}</td>
        </tr>
        <tr>
            <td class="info-label">Contribuinte:</td>
            <td>{{ $imovel->contribuinte->nome ?? 'N/A' }}</td>
            <td class="info-label">Tipo:</td>
            <td>{{ $imovel->tipo }}</td>
        </tr>
        <tr>
            <td class="info-label">Bairro:</td>
            <td>{{ $imovel->bairro }}</td>
            <td class="info-label">Logradouro:</td>
            <td>{{ $imovel->logradouro }}</td>
        </tr>
        <tr>
            <td class="info-label">Número:</td>
            <td>{{ $imovel->numero }}</td>
            <td class="info-label">Complemento:</td>
            <td>{{ $imovel->complemento ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Área do Terreno:</td>
            <td>{{ number_format($imovel->area_terreno, 2, ',', '.') }}m²</td>
            <td class="info-label">Área da Edificação:</td>
            <td>{{ number_format($areaTotal, 2, ',', '.') }}m²</td>
        </tr>
    </table>

    @if($imovel->averbacoes->count() > 0)
    <div class="info-section">
        <div class="info-title">Averbações</div>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($imovel->averbacoes as $averbacao)
                <tr>
                    <td>{{ $averbacao->data->format('d/m/Y') }}</td>
                    <td>{{ $averbacao->descricao }}</td>
                    <td>{{ $averbacao->tipo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="info-section">
        <div class="info-title">Averbações</div>
        <div class="no-data">Nenhuma averbação registrada para este imóvel</div>
    </div>
    @endif

    <div class="footer">
        Gerado em: {{ $dataGeracao }}
    </div>
</body>
</html>