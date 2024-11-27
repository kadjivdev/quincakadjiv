<!-- Modal -->
<div class="modal fade" id="staticBackdrop{{ $appro->id }}" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('livraisons.update', $appro->id) }}" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Valider Appro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <div class="shadow shadow-lg p-2">
    
                        <div class="col-12 mb-3">
                            <label for="" class="form-label">Article livré</label>
                            <input type="text" readonly
                                value="{{ $appro->article_nom }}" required
                                class="form-control" >
                            <input type="hidden" value="{{ $appro->article_id }}"
                                name="article_id">
                        </div>
    
                        <div class="col-12 mb-3">
                            <label for="" class="form-label">Unité de mesure</label>
                            <input type="text" readonly
                                value="{{ $appro->unite }}" required
                                class="form-control" >
                        </div>
    
                        <div class="col-12 mb-3">
                            <label for="" class="form-label">Quantité livrée </label>
                            <input type="text" required name="qte_livre" min="0" max="{{ $appro->qte_livre }}"
                                value="{{ $appro->qte_livre }}" required
                                class="form-control" >
                        </div>
    
                        <div class="col-12 mb-3">
                            <label class="form-label">Magasin de livraison</label>
                            <select class="form-select js-example-basic-multiple" name="magasin_id" id="magasinSelect">
                                <option value="">Choisir le magasin </option>
                                @foreach ($magasins as $magasin)
                                <option value="{{ $magasin->id }}"
                                    {{ $magasin->id == $appro->magasin_id ? 'selected' : '' }}>
                                    {{ $magasin->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
