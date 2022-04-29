<form class="text-left" id="">
@csrf
    <div class="form-row">
        <div class="col-md-12 mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="typeArchive" id="typeArchive1" value="ipe" onclick="freeCompetence()" />
                <label class="form-check-label" for="massive1">IPE</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="typeArchive" id="typeArchive2" value="tesouro" onclick="freeCompetence()" />
                <label class="form-check-label" for="massive2">Tesouro</label>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <label>Competência</label>
            <select class="form-control" id="selCompetitionDropBill" onchange="loadUploadDropBill()" disabled>
                <option value="" selected disabled>Selecione</option>
                @foreach( $competitionList as $com )
                    <option value="{{ $com->com_nome }}" <?php if($com->com_nome == $currentCompetence) echo 'selected'; ?> >{{ $com->com_nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12 mb-3">
            <div id="fileuploaderDropBill"></div>
            <div id="retornoDropBill"></div>
        </div>
    </div>
</form>
<script>
</script>


