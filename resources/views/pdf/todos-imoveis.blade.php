<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório Geral</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 10px;}
        .header { text-align: center; margin-bottom: 20px; }
        .logo { max-width: 150px; margin-bottom: 10px; }
        .empresa-nome { font-size: 24px; font-weight: bold; margin: 10px 0; }
        .subtitulo { font-size: 16px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; word-wrap: break-word; }
        th, td { border: 1px solid #ddd; padding: 4px; text-align: center; }
        th { background-color: #f5f5f5; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <img src="img/logo.png" class="logo" alt="Logo">
        <div class="empresa-nome">Prefeitura Municipal de São Leopoldo</div>
        <div class="subtitulo">Relatório Geral de Imóveis Cadastrados</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Inscrição Municipal</th>
                <th>Contribuinte</th>
                <th>Tipo</th>
                <th>Logradouro</th>
                <th>Número</th>
                <th>Bairro</th>
                <th>Área Terreno</th>
                <th>Área Edificação</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($imoveis as $imovel)
            <tr>
                <td>{{ $imovel['inscricao_municipal'] }}</td>
                <td>{{ $imovel['contribuinte'] }}</td>
                <td>{{ $imovel['tipo'] }}</td>
                <td>{{ $imovel['logradouro'] }}</td>
                <td>{{ $imovel['numero'] }}</td>
                <td>{{ $imovel['bairro'] }}</td>
                <td>{{ number_format($imovel['area_terreno'], 2, ',', '.') }}</td>
                <td>{{ number_format($imovel['area_edificacao'], 2, ',', '.') }}</td>
                <td>{{ $imovel['situacao'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Gerado em: {{ $dataGeracao }}
    </div>
</body>
</html>