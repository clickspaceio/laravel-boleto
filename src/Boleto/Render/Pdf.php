<?php

namespace Eduardokum\LaravelBoleto\Boleto\Render;

use Eduardokum\LaravelBoleto\Contracts\Boleto\Boleto as BoletoContract;
use Eduardokum\LaravelBoleto\Contracts\Boleto\Render\Pdf as PdfContract;
use Eduardokum\LaravelBoleto\Util;

class Pdf extends AbstractPdf implements PdfContract
{
    const OUTPUT_STANDARD = 'I';
    const OUTPUT_DOWNLOAD = 'D';
    const OUTPUT_SAVE = 'F';
    const OUTPUT_STRING = 'S';

    private $pagclickUrl = null;
    private $pagclickLogo = "data:image/png;base64,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";

    private $PadraoFont = 'Arial';
    /**
     * @var BoletoContract[]
     */
    private $boleto = array();

    /**
     * @var bool
     */
    private $print = false;

    /**
     * @var bool
     */
    private $showInstrucoes = true;


    private $desc = 3; // tamanho célula descrição
    private $cell = 4; // tamanho célula dado
    private $fdes = 6; // tamanho fonte descrição
    private $fcel = 8; // tamanho fonte célula
    private $small = 0.2; // tamanho barra fina
    private $totalBoletos = 0;

    public function __construct()
    {
        parent::__construct('P', 'mm', 'A4');
        $this->SetAutoPageBreak(false);
        $this->SetLeftMargin(20);
        $this->SetTopMargin(15);
        $this->SetRightMargin(20);
        $this->SetLineWidth($this->small);
    }

    /**
     * @param integer $i
     *
     * @return $this
     */
    protected function instrucoes($i)
    {
        $this->SetFont($this->PadraoFont, '', 8);
        if ($this->totalBoletos > 1) {
            $this->SetAutoPageBreak(true);
            $this->SetY(5);
            $this->Cell(30, 10, date('d/m/Y H:i:s'));
            $this->Cell(0, 10, "Boleto " . ($i + 1) . " de " . $this->totalBoletos, 0, 1, 'R');
        }

        $this->SetFont($this->PadraoFont, 'B', 8);
        if ($this->showInstrucoes) {
            $this->SetFont($this->PadraoFont, '', $this->fcel);
            $this->Cell(25, $this->cell, $this->_('Linha Digitável: '), 0, 0);
            $this->SetFont($this->PadraoFont, 'B', $this->fcel);
            $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getLinhaDigitavel()), 0, 1);
            $this->SetFont($this->PadraoFont, '', $this->fcel);
            $this->Cell(25, $this->cell, $this->_('Número: '), 0, 0);
            $this->SetFont($this->PadraoFont, 'B', $this->fcel);
            $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getNumero()), 0, 1);
            $this->SetFont($this->PadraoFont, '', $this->fcel);

        }

        return $this;
    }

    protected function resizeImage($oldSize, $newWidth, $newHeight = null) {

        $size = $oldSize;

        if ($newWidth) {

            $size[0] = $newWidth;
            $size[1] = $newWidth/$oldSize[0] * $size[1];

        } elseif ($newHeight) {

            $size[1] = $newHeight;
            $size[0] = $newHeight/$oldSize[1] * $size[0];

        }

        return $size;

    }

    /**
     * @param integer $i
     *
     * @return $this
     */
    protected function logoEmpresa($i)
    {
        $this->Ln(2);
        $this->SetFont($this->PadraoFont, '', $this->fdes);

        $logo = preg_replace('/\&.*/', '', $this->boleto[$i]->getLogo());
        $ext = pathinfo($logo, PATHINFO_EXTENSION);

        $y = 0;

        if ($this->boleto[$i]->getLogo() && !empty($this->boleto[$i]->getLogo())) {
            $logoSize = getimagesize($this->boleto[$i]->getLogo());

            $w = (($logoSize[0] * 2.54) / 9.6);
            $h = (($logoSize[1] * 2.54) / 9.6);

            if ($w > 170) {
                $size = $this->resizeImage([$w, $h], 170);
                $w = $size[0];
                $h = $size[1];
            }

            if ($h > 70) {
                $size = $this->resizeImage([$w, $h], null, 70);
                $w = $size[0];
                $h = $size[1];
            }

            $y += $h + 3;

            $image = $this->Image($this->boleto[$i]->getLogo(), 20, ($this->GetY()), $w, $h, $ext);
        }


        $this->Ln(1+$y);

        return $this;
    }

    /**
     * @param integer $i
     *
     * @return $this
     */
    protected function Topo($i)
    {

        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(120, $this->desc, $this->_('Beneficiário'), 'TLR');
        $this->Cell(50, $this->desc, $this->_('Vencimento'), 'TR', 1);

        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        $this->textFitCell(120, $this->cell, $this->_($this->boleto[$i]->getBeneficiario()->getNome()), 'LR', 0, 'L');
        $this->Cell(50, $this->cell, $this->_($this->boleto[$i]->getDataVencimento()->format('d/m/Y')), 'R', 1);



        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(40, $this->desc, $this->_('Data do Documento'), 'TLR');
        $this->Cell(40, $this->desc, $this->_('No. do Documento'), 'TR');
        $this->Cell(40, $this->desc, $this->_('(=) Valor do Documento'), 'TR');
        $this->Cell(50, $this->desc, $this->_('(=) Valor Cobrado'), 'TR', 1);

        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        $this->Cell(40, $this->cell, $this->_($this->boleto[$i]->getDataDocumento()->format('d/m/Y')), 'LR');
        $this->Cell(40, $this->cell, $this->_($this->boleto[$i]->getNumeroDocumento()), 'R');
        $this->Cell(40, $this->cell, $this->_(Util::nReal($this->boleto[$i]->getValor())), 'R');
        $this->Cell(50, $this->cell, $this->_(''), 'R', 1, 'R');



        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(170, $this->desc, $this->_('Demonstrativo'), 'TLR', 1, 'L');

        $pulaLinha = 10;
        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        if (count($this->boleto[$i]->getDescricaoDemonstrativo()) > 0) {
            $pulaLinha = $this->listaLinhas($this->boleto[$i]->getDescricaoDemonstrativo(), $pulaLinha, 'LR');
        }



        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(0, $this->desc, $this->_('Pagador'), 'TLR', 1);

        $this->Image($this->pagclickLogo, 168, ($this->GetY()+2.5), 0, 0, 'png', $this->pagclickUrl);

        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getPagador()->getNomeDocumento()), 'LR', 1);
        $this->Cell(0, $this->cell, $this->_(trim($this->boleto[$i]->getPagador()->getEndereco() . ' - ' . $this->boleto[$i]->getPagador()->getBairro()), ' -'), 'LR', 1);
        $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getPagador()->getCepCidadeUf()), 'LR', 1);



        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(100, $this->desc, $this->_('Sacador/Avalista'), 'T', 0);
        $this->Cell(70, $this->desc, $this->_('Autenticação mecânica - Recibo do Pagador'), 'T', 1, 'R');


        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getSacadorAvalista()->getNomeDocumento()), '', 1);
        $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getSacadorAvalista()->getEndereco() ? trim($this->boleto[$i]->getSacadorAvalista()->getEndereco() . ' - ' . $this->boleto[$i]->getSacadorAvalista()->getBairro()) : '', ' -'), '', 1);
        $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getSacadorAvalista()->getCepCidadeUf()), '', 1);



        $this->Ln(1);

        $this->traco('Corte na linha pontilhada', $pulaLinha, 10);

        return $this;
    }

    /**
     * @param integer $i
     *
     * @return $this
     */
    protected function Bottom($i)
    {
        $this->Image($this->boleto[$i]->getLogoBanco(), 20, ($this->GetY() - 2), 7);
        $this->Cell(8, 6, '', 'B');
        $this->SetFont($this->PadraoFont, 'B', 11);
        $this->Cell(35, 6, $this->_("Banco Itaú S/A"), '', 0, 'L');

        $this->SetFont($this->PadraoFont, 'B', 13);
        $this->Cell(15, 6, $this->boleto[$i]->getCodigoBancoComDv(), 'LBR', 0, 'C');
        $this->SetFont($this->PadraoFont, 'B', 10);
        $this->Cell(0, 6, $this->boleto[$i]->getLinhaDigitavel(), 'B', 1, 'R');

        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(120, $this->desc, $this->_('Local de pagamento'), 'TLR');
        $this->Cell(50, $this->desc, $this->_('Vencimento'), 'TR', 1);

        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        $this->Cell(120, $this->cell, $this->_($this->boleto[$i]->getLocalPagamento()), 'LR');
        $this->Cell(50, $this->cell, $this->_($this->boleto[$i]->getDataVencimento()->format('d/m/Y')), 'R', 1, 'R');

        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(120, $this->desc, $this->_('Beneficiário'), 'TLR');
        $this->Cell(50, $this->desc, $this->_('Agência/Código beneficiário'), 'TR', 1);

        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        $this->Cell(120, $this->cell, $this->_($this->boleto[$i]->getBeneficiario()->getNomeDocumento()), 'LR');
        $xBeneficiario = $this->GetX();
        $yBeneficiario = $this->GetY();
        $this->Cell(50, $this->cell, $this->_($this->boleto[$i]->getAgenciaCodigoBeneficiario()), 'R', 1, 'R');
        if($this->boleto[$i]->getMostrarEnderecoFichaCompensacao()) {
            $this->SetXY($xBeneficiario, $yBeneficiario);
            $this->Ln(4);
            $this->SetFont($this->PadraoFont, 'B', $this->fcel);
            $this->Cell(120, $this->cell, $this->_($this->boleto[$i]->getBeneficiario()->getEnderecoCompleto()), 'LR');
            $this->Cell(50, $this->cell, "", 'R', 1, 'R');
        }

        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(30, $this->desc, $this->_('Data do documento'), 'TLR');
        $this->Cell(40, $this->desc, $this->_('Número do documento'), 'TR');
        $this->Cell(15, $this->desc, $this->_('Espécie Doc.'), 'TR');
        $this->Cell(10, $this->desc, $this->_('Aceite'), 'TR');
        $this->Cell(25, $this->desc, $this->_('Data processamento'), 'TR');
        $this->Cell(50, $this->desc, $this->_('Nosso número'), 'TR', 1);

        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        $this->Cell(30, $this->cell, $this->_($this->boleto[$i]->getDataDocumento()->format('d/m/Y')), 'LR');
        $this->Cell(40, $this->cell, $this->_($this->boleto[$i]->getNumeroDocumento()), 'R');
        $this->Cell(15, $this->cell, $this->_($this->boleto[$i]->getEspecieDoc()), 'R');
        $this->Cell(10, $this->cell, $this->_($this->boleto[$i]->getAceite()), 'R');
        $this->Cell(25, $this->cell, $this->_($this->boleto[$i]->getDataProcessamento()->format('d/m/Y')), 'R');
        $this->Cell(50, $this->cell, $this->_($this->boleto[$i]->getNossoNumeroBoleto()), 'R', 1, 'R');

        $this->SetFont($this->PadraoFont, '', $this->fdes);

        if (isset($this->boleto[$i]->variaveis_adicionais['esconde_uso_banco']) && $this->boleto[$i]->variaveis_adicionais['esconde_uso_banco']) {
            $this->Cell(55, $this->desc, $this->_('Carteira'), 'TLR');
        } else {
            $cip = isset($this->boleto[$i]->variaveis_adicionais['mostra_cip']) && $this->boleto[$i]->variaveis_adicionais['mostra_cip'];

            $this->Cell(($cip ? 23 : 30), $this->desc, $this->_('Uso do Banco'), 'TLR');
            if ($cip) {
                $this->Cell(7, $this->desc, $this->_('CIP'), 'TLR');
            }
            $this->Cell(25, $this->desc, $this->_('Carteira'), 'TR');
        }

        $this->Cell(12, $this->desc, $this->_('Espécie'), 'TR');
        $this->Cell(28, $this->desc, $this->_('Quantidade'), 'TR');
        $this->Cell(25, $this->desc, $this->_(($this->boleto[$i]->getCodigoBanco() == '104') ? 'xValor' : 'Valor Documento'), 'TR');
        $this->Cell(50, $this->desc, $this->_('Valor Documento'), 'TR', 1);

        $this->SetFont($this->PadraoFont, 'B', $this->fcel);

        if (isset($this->boleto[$i]->variaveis_adicionais['esconde_uso_banco']) && $this->boleto[$i]->variaveis_adicionais['esconde_uso_banco']) {
            $this->TextFitCell(55, $this->cell, $this->_($this->boleto[$i]->getCarteiraNome()), 'LR', 0, 'L');
        } else {
            $cip = isset($this->boleto[$i]->variaveis_adicionais['mostra_cip']) && $this->boleto[$i]->variaveis_adicionais['mostra_cip'];
            $this->Cell(($cip ? 23 : 30), $this->cell, $this->_(''), 'LR');
            if ($cip) {
                $this->Cell(7, $this->cell, $this->_($this->boleto[$i]->getCip()), 'LR');
            }
            $this->Cell(25, $this->cell, $this->_(strtoupper($this->boleto[$i]->getCarteiraNome())), 'R');
        }

        $this->Cell(12, $this->cell, $this->_('R$'), 'R');
        $this->Cell(28, $this->cell, $this->_(''), 'R');
        $this->Cell(25, $this->cell, $this->_(''), 'R');
        $this->Cell(50, $this->cell, $this->_(Util::nReal($this->boleto[$i]->getValor())), 'R', 1, 'R');

        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(120, $this->desc, $this->_("Instruções"), 'TLR');
        $this->Cell(50, $this->desc, $this->_('(-) Desconto / Abatimentos'), 'TR', 1);

        $xInstrucoes = $this->GetX();
        $yInstrucoes = $this->GetY();

        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(120, $this->cell, $this->_(''), 'LR');
        $this->Cell(50, $this->cell, $this->_(''), 'R', 1);

        $this->Cell(120, $this->desc, $this->_(''), 'LR');
        $this->Cell(50, $this->desc, $this->_('(-) Outras deduções'), 'TR', 1);

        $this->Cell(120, $this->cell, $this->_(''), 'LR');
        $this->Cell(50, $this->cell, $this->_(''), 'R', 1);

        $this->Cell(120, $this->desc, $this->_(''), 'LR');
        $this->Cell(50, $this->desc, $this->_('(+) Mora / Multa'), 'TR', 1);

        $this->Cell(120, $this->cell, $this->_(''), 'LR');
        $this->Cell(50, $this->cell, $this->_(''), 'R', 1);

        $this->Cell(120, $this->desc, $this->_(''), 'LR');
        $this->Cell(50, $this->desc, $this->_('(+) Outros acréscimos'), 'TR', 1);

        $this->Cell(120, $this->cell, $this->_(''), 'LR');
        $this->Cell(50, $this->cell, $this->_(''), 'R', 1);

        $this->Cell(120, $this->desc, $this->_(''), 'LR');
        $this->Cell(50, $this->desc, $this->_('(=) Valor cobrado'), 'TR', 1);

        $this->Cell(120, $this->cell, $this->_(''), 'BLR');
        $this->Cell(50, $this->cell, $this->_(''), 'BR', 1);

        $this->Image($this->pagclickLogo, 168, ($this->GetY()+5.5), 0, 0, 'png', $this->pagclickUrl);

        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(0, $this->desc, $this->_('Pagador'), 'LR', 1);

        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
        $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getPagador()->getNomeDocumento()), 'LR', 1);
        $this->Cell(0, $this->cell, $this->_(trim($this->boleto[$i]->getPagador()->getEndereco() . ' - ' . $this->boleto[$i]->getPagador()->getBairro()), ' -'), 'LR', 1);
        $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getPagador()->getCepCidadeUf()), 'BLR', 1);


        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->Cell(20, $this->desc, $this->_('Sacador/Avalista'), 0);
        $this->Cell(98, $this->desc, $this->_($this->boleto[$i]->getSacadorAvalista() ? $this->boleto[$i]->getSacadorAvalista()->getNomeDocumento() : ''), 0);
        $this->Cell(52, $this->desc, $this->_('Autenticação mecânica - Ficha de Compensação'), 0, 1, 'R');

        $xOriginal = $this->GetX();
        $yOriginal = $this->GetY();

        if (count($this->boleto[$i]->getInstrucoes()) > 0) {
            $this->SetXY($xInstrucoes, $yInstrucoes);
            $this->Ln(1);
            $this->SetFont($this->PadraoFont, 'B', $this->fcel);

            $this->listaLinhas($this->boleto[$i]->getInstrucoes(), 0);

            $this->SetXY($xOriginal, $yOriginal);
        }
        return $this;
    }

    /**
     * @param      string $texto
     * @param integer $ln
     * @param integer $ln2
     */
    protected function traco($texto, $ln = null, $ln2 = null)
    {
        if ($ln == 1 || $ln) {
            $this->Ln($ln);
        }
        $this->SetFont($this->PadraoFont, '', $this->fdes);
        if ($texto) {
            $this->Cell(0, 2, $this->_($texto), 0, 1, 'R');
        }
        $this->Cell(0, 2, str_pad('-', '261', ' -', STR_PAD_RIGHT), 0, 1);
        if ($ln2 == 1 || $ln2) {
            $this->Ln($ln2);
        }
    }

    /**
     * @param integer $i
     */
    protected function codigoBarras($i)
    {
        $this->Ln(3);
        $this->Cell(0, 15, '', 0, 1, 'L');
        $this->i25($this->GetX(), $this->GetY() - 15, $this->boleto[$i]->getCodigoBarras(), 0.8, 17);
    }

    /**
     * Addiciona o boletos
     *
     * @param array $boletos
     * @param bool $withGroup
     *
     * @return $this
     */
    public function addBoletos(array $boletos, $withGroup = true)
    {
        if ($withGroup) {
            $this->StartPageGroup();
        }

        foreach ($boletos as $boleto) {
            $this->addBoleto($boleto);
        }

        return $this;
    }

    /**
     * Addiciona o boleto
     *
     * @param BoletoContract $boleto
     *
     * @return $this
     */
    public function addBoleto(BoletoContract $boleto)
    {
        $this->totalBoletos += 1;
        $this->boleto[] = $boleto;
        return $this;
    }

    /**
     * @return $this
     */
    public function hideInstrucoes()
    {
        $this->showInstrucoes = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function showPrint()
    {
        $this->print = true;
        return $this;
    }

    /**
     * função para gerar o boleto
     *
     * @param string $dest tipo de destino const BOLETOPDF_DEST_STANDARD | BOLETOPDF_DEST_DOWNLOAD | BOLETOPDF_DEST_SAVE | BOLETOPDF_DEST_STRING
     * @param null $save_path
     *
     * @return string
     * @throws \Exception
     */
    public function gerarBoleto($dest = self::OUTPUT_STANDARD, $save_path = null, $nameFile = null)
    {
        if ($this->totalBoletos == 0) {
            throw new \Exception('Nenhum Boleto adicionado');
        }

        for ($i = 0; $i < $this->totalBoletos; $i++) {
            $this->SetDrawColor('0', '0', '0');
            $this->AddPage();
            $this->logoEmpresa($i)->Topo($i)->Bottom($i)->codigoBarras($i);
        }
        if ($dest == self::OUTPUT_SAVE) {
            $this->Output($save_path, $dest, $this->print);
            return $save_path;
        }
        if ($nameFile == null) {
            $nameFile = str_random(32);
        }
        
        return $this->Output($nameFile . '.pdf', $dest, $this->print);
    }

    /**
     * @param $lista
     * @param integer $pulaLinha
     *
     * @return int
     */
    private function listaLinhas($lista, $pulaLinha, $border = 0)
    {
        foreach ($lista as $d) {
            $pulaLinha -= 2;
            $this->MultiCell(0, $this->cell - 0.2, $this->_(preg_replace('/(%)/', '%$1', $d)), $border, 1);
        }

        return $pulaLinha;
    }
}
