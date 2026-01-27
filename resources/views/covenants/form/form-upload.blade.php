<form class="text-left" id="">
@csrf
    <div class="form-row">
        <div class="col-md-12 mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="typeArchive" id="typeArchive1" value="ipe" onclick="freeCompetence()" />
                <label class="form-check-label" for="typeArchive1">IPE</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="typeArchive" id="typeArchive2" value="tesouro" onclick="freeCompetence()" />
                <label class="form-check-label" for="typeArchive2">Tesouro</label>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <a id="model_ipe" href="modelo_ipe.txt" target="_blank" class="btn btn-outline-info btn-sm mb-2 models">Arquivo modelo IPE</a>
            <a id="model_tesouro" href="modelo_tesouro.txt" target="_blank" class="btn btn-outline-info btn-sm mb-2 models">Arquivo modelo Tesouro</a>
        </div>
        <div class="col-md-12 mb-3">
            <label>Competência</label>
            <select class="form-control" id="selCompetitionDropBill" disabled>
                <option value="" selected disabled>Selecione</option>
                @foreach( $competitionList as $com )
                    <option value="{{ $com->com_nome }}" <?php if($com->com_nome == date('m/Y')) echo "selected";?>>{{ $com->com_nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12 mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="extensionArchive" id="extensionArchive1" value="txt" selected />
                <label class="form-check-label" for="extensionArchive1">TXT</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="extensionArchive" id="extensionArchive2" value="xlsx" />
                <label class="form-check-label" for="extensionArchive2">XLSX</label>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div id="fileuploaderDropBill"></div>
            <div id="retornoDropBill"></div>
        </div>
    </div>
</form>
<script>
</script>


