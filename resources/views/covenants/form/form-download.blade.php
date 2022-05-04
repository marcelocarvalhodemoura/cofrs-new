<?php
    use Carbon\Carbon;
    $currentDate = Carbon::now('America/Sao_Paulo');

    $currentMonth = str_pad($currentDate->month, 2, '0', STR_PAD_LEFT);

    $currentYear = $currentDate->year;

    $yearCompetence = [
        '2019' => '2019',
        '2020' => '2020',
        '2021' => '2021',
        '2022' => '2022',
        '2023' => '2023',
        '2024' => '2024',
        '2025' => '2025',
        '2026' => '2026',
        '2027' => '2027',
        '2028' => '2028',
        '2029' => '2029',
        '2030' => '2030',
        '2031' => '2031',
        '2032' => '2032',
        '2033' => '2033',
        '2034' => '2034',
        '2035' => '2035',
        '2036' => '2036',
        '2037' => '2037',
        '2038' => '2038',
        '2039' => '2039',
        '2040' => '2040',
        '2041' => '2041',
        '2042' => '2042',
        '2043' => '2043',
        '2044' => '2044',
        '2045' => '2045',
        '2046' => '2046',
        '2047' => '2047',
        '2048' => '2048',
        '2049' => '2049',
        '2050' => '2050',
        '2051' => '2051',
        '2052' => '2052',
        '2053' => '2053',
        '2054' => '2054',
        '2055' => '2055',
        '2056' => '2056',
        '2057' => '2057',
        '2058' => '2058',
        '2059' => '2059',
        '2060' => '2060',
        '2061' => '2061',
        '2062' => '2062',
        '2063' => '2063',
        '2064' => '2064',
    ];

    $monthCompetence = [
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro',
    ];
?>
<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formDowloadFile">
    @csrf
    <fieldset class="form-group mb-4" id="fieldset1">
        <div class="row">
            <label class="col-form-label col-xl-2 col-sm-3 col-sm-2 pt-0">Selecione a competência</label>
            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control custom-checkbox checkbox-info">

                        <select class="form-control" id="monthCompetence" name="monthCompetence" required>
                            <option value="">Selecione o mês da competencia</option>
                            @foreach($monthCompetence as $key => $value)
                                @if($key == $currentMonth)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control ">
                        <select class="form-control" id="yearCompetence" name="yearCompetence" required>
                            <option value="">Selecione o ano</option>
                            @foreach($yearCompetence as $key => $value)
                                @if($key == $currentYear)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="form-group mb-4" id="fieldset2">
        <div class="row">
            <label class="col-form-label col-xl-2 col-sm-3 col-sm-2 pt-0">Selecione a  Instituição</label>
            <div class="col-xl-10 col-lg-9 col-sm-10">
                <div class="form-check mb-2">
                    <div class="custom-control custom-radio classic-radio-info">
                        <input type="radio" id="company1" name="company[]" class="custom-control-input" value="Ipe" checked>
                        <label class="custom-control-label" for="company1">Ipe</label>
                    </div>
                </div>
                <div class="form-check mb-2">
                    <div class="custom-control custom-radio classic-radio-info">
                        <input type="radio" id="company2" name="company[]" class="custom-control-input" value="Tesouro">
                        <label class="custom-control-label" for="company2">Tesouro do Estado</label>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <hr />
    <fieldset class="form-group mb-4" id="fieldset3">
        <div class="row">

            <label class="col-form-label col-xl-2 col-sm-3 col-sm-2 pt-0">Selecione o Tipo</label>

            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control custom-checkbox checkbox-info">
                        <input type="checkbox" class="custom-control-input" name="convenants[]" id="convenants2" value="DIVERSOS" checked>
                        <label class="custom-control-label" for="convenants2">Diversos</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control custom-checkbox checkbox-info">
                        <input type="checkbox" class="custom-control-input" name="convenants[]" value="MENSALIDADE" id="convenants1" checked>
                        <label class="custom-control-label" for="convenants1">Mensalidade</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control custom-checkbox checkbox-info">
                        <input type="checkbox" class="custom-control-input" name="convenants[]" id="convenants3" value="EMPRESTIMO" checked>
                        <label class="custom-control-label" for="convenants3">Emprestimos</label>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="form-group mb-4" id="fieldsetFile" style="display: none;">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control custom-checkbox checkbox-info">
                        <textarea className="form-control" id="createdFile" rows="15" cols="125"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnCreateFile" type="submit">Gerar Arquivo</button>
    </div>
</form>
