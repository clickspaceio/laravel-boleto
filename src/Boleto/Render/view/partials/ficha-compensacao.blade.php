<table class="table-boleto" cellpadding="0" cellspacing="0" border="0">
    <tbody>
    <tr>
        <td valign="bottom" colspan="8" class="noborder nopadding">
            <div class="logocontainer">
                <div class="logobanco">
                    <img src="{{ isset($logo_banco_base64) && !empty($logo_banco_base64) ? $logo_banco_base64 : 'https://dummyimage.com/150x75/fff/000000.jpg&text=+' }}" alt="logo do banco">
                </div>
                <div class="nomebanco">{{ $codigo_banco_com_dv == "341-7" ? "Banco Itaú S/A" : "" }}</div>
                <div class="codbanco">{{ $codigo_banco_com_dv }}</div>
            </div>
            <div class="linha-digitavel">{{ $linha_digitavel }}</div>
        </td>
    </tr>
    <tr>
        <td colspan="7" class="top-2">
            <div class="titulo">Local de pagamento</div>
            <div class="conteudo">{{ $local_pagamento }}</div>
        </td>
        <td width="180" class="top-2">
            <div class="titulo">Vencimento</div>
            <div class="conteudo rtl">{{ $data_vencimento->format('d/m/Y') }}</div>
        </td>
    </tr>
    <tr class="@if($mostrar_endereco_ficha_compensacao) duas-linhas @endif">
        <td colspan="7">
            <div class="titulo">Beneficiário</div>
            <div class="conteudo">{{ $beneficiario['nome_documento'] }}</div>
            @if($mostrar_endereco_ficha_compensacao)<div class="conteudo">{{ $beneficiario['endereco_completo'] }}</div>@endif
        </td>
        <td>
            <div class="titulo">Agência/Código beneficiário</div>
            <div class="conteudo rtl">{{ $agencia_codigo_beneficiario }}</div>
        </td>
    </tr>
    <tr>
        <td width="110" colspan="2">
            <div class="titulo">Data do documento</div>
            <div class="conteudo">{{ $data_documento->format('d/m/Y') }}</div>
        </td>
        <td width="120" colspan="2">
            <div class="titulo">Nº documento</div>
            <div class="conteudo">{{ $numero_documento }}</div>
        </td>
        <td width="60">
            <div class="titulo">Espécie doc.</div>
            <div class="conteudo">{{ $especie_doc }}</div>
        </td>
        <td>
            <div class="titulo">Aceite</div>
            <div class="conteudo">{{ $aceite }}</div>
        </td>
        <td width="110">
            <div class="titulo">Data processamento</div>
            <div class="conteudo">{{ $data_processamento->format('d/m/Y') }}</div>
        </td>
        <td>
            <div class="titulo">Nosso número</div>
            <div class="conteudo rtl">{{ $nosso_numero_boleto }}</div>
        </td>
    </tr>
    <tr>
        @if(!isset($esconde_uso_banco) || !$esconde_uso_banco)
            <td {{ !isset($mostra_cip) || !$mostra_cip ? 'colspan=2' : ''}}>
                <div class="titulo">Uso do banco</div>
                <div class="conteudo">{{ $uso_banco }}</div>
            </td>
            @endif
            @if (isset($mostra_cip) && $mostra_cip)
                    <!-- Campo exclusivo do Bradesco -->
            <td width="20">
                <div class="titulo">CIP</div>
                <div class="conteudo">{{ $cip }}</div>
            </td>
        @endif

        <td {{isset($esconde_uso_banco) && $esconde_uso_banco ? 'colspan=3': '' }}>
            <div class="titulo">Carteira</div>
            <div class="conteudo">{{ $carteira_nome }}</div>
        </td>
        <td width="35">
            <div class="titulo">Espécie</div>
            <div class="conteudo">{{ $especie }}</div>
        </td>
        <td colspan="2">
            <div class="titulo">Quantidade</div>
            <div class="conteudo"></div>
        </td>
        <td width="110">
            <div class="titulo">Valor</div>
            <div class="conteudo"></div>
        </td>
        <td>
            <div class="titulo">(=) Valor do Documento</div>
            <div class="conteudo rtl">{{ $valor }}</div>
        </td>
    </tr>
    <tr>
        <td colspan="7" style="vertical-align: top">
            <div class="titulo">Instruções</div>
            <div class="conteudo">{{ $instrucoes[0] }}</div>
            <div class="conteudo">{{ $instrucoes[1] }}</div>
            <div class="conteudo">{{ $instrucoes[2] }}</div>
            <div class="conteudo">{{ $instrucoes[3] }}</div>
            <div class="conteudo">{{ $instrucoes[4] }}</div>
            <div class="conteudo">{{ $instrucoes[5] }}</div>
            <div class="conteudo">{{ $instrucoes[6] }}</div>
            <div class="conteudo">{{ $instrucoes[7] }}</div>
        </td>
        <td style="padding: 0">
            <table class="table-clean-design">
                <tr>
                    <td class="norightborder noleftborder notopborder">
                        <div class="titulo">(-) Descontos / Abatimentos</div>
                        <div class="conteudo rtl"></div>
                    </td>
                </tr>
                <tr>
                    <td class="norightborder noleftborder">
                        <div class="titulo">(-) Outras deduções</div>
                        <div class="conteudo rtl"></div>
                    </td>
                </tr>
                <tr>
                    <td class="norightborder noleftborder">
                        <div class="titulo">(+) Mora / Multa {{ $codigo_banco == '104' ? '/ Juros' : '' }}</div>
                        <div class="conteudo rtl"></div>
                    </td>
                </tr>
                <tr>
                    <td class="norightborder noleftborder">
                        <div class="titulo">(+) Outros acréscimos</div>
                        <div class="conteudo rtl"></div>
                    </td>
                </tr>
                <tr>
                    <td class="norightborder noleftborder nobottomborder">
                        <div class="titulo">(=) Valor cobrado</div>
                        <div class="conteudo rtl"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="7">
            <div class="titulo">Pagador</div>
            <div class="conteudo">{{ $pagador['nome_documento'] }}</div>
            <div class="conteudo">{{ $pagador['endereco'] }}</div>
            <div class="conteudo">{{ $pagador['endereco2'] }}</div>

        </td>
        <td class="noleftborder">
            <div class="titulo" style="margin-top: 10px; margin-right: 10px; text-align: right">
                <a href="https://www.pagclick.com.br" target="_blank">
                    <img src="data:image/png;base64,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" alt="Pagclick" />
                </a>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="6" class="noleftborder">
            <div class="titulo">Sacador/Avalista
                <div class="conteudo sacador">{{ $sacador_avalista ? $sacador_avalista['nome_documento'] : '' }}</div>
            </div>
        </td>
        <td colspan="2" class="norightborder noleftborder">
            <div class="conteudo noborder rtl" style="font-weight: normal">Autenticação mecânica - Ficha de Compensação</div>
        </td>
    </tr>

    <tr>
        <td colspan="8" class="noborder">
            {!! $codigo_barras !!}
        </td>
    </tr>

    </tbody>
</table>