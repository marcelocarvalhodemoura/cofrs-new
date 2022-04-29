<form class="text-left" id="">
@csrf
    <div class="form-row">
        <div class="col-md-12 mb-3">
            <label>Competência</label>
            <select class="form-control" id="selCompetitionDropBill" onchange="loadUploadDropBill()">
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


