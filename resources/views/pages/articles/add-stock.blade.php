<!-- Modal -->
<div class="modal fade" id="stockModal{{ $article->id }}" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="stockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('article-stock') }}" class="" method="post">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="stockModalLabel">Enregistrer le stock</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="p-3 shadow shadow-lg">
                        <div class="col-12 mb-3">
                            <label for="" class="form-label">Article</label>
                            <input type="text" readonly value="{{ $article->nom }}" required class="form-control"
                                name="unite">
                            <input type="hidden" value="{{ $article->id }}" name="article_id">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Quantité du stock</label>
                            <input type="text" class="form-control" value="{{ old('qte_stock')}}" name="qte_stock">
                        </div>

                        <div class="col-12 mb-3">
                            <label for="">Unité de mesure </label>
                            <select name="unite_mesure_id" id="unite_mesure_id" class="form-control">
                                <option value="">Choisir l'unité à convertir
                                </option>
                                @foreach ($unites as $unite)
                                <option value="{{ $unite->id }}">
                                    {{ $unite->unite }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Point de vente </label>
                            <select name="point_vente_id" id="points" class="form-control">
                                <option value="">Choisir le point de vente
                                </option>
                                @foreach ($points as $point)
                                <option value="{{ $point->id }}">
                                    {{ $point->nom }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100" onclick="submit()"><span class="spinner-border spinner-border-sm text_orange loading" hidden></span> <i class="bi bi-check-circle" id="submit_icon"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function submit() {
        $(".loading").removeAttr("hidden")
        $("#submit_icon").attr("hidden", "hidden")
    }
</script>