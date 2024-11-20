<!-- Modal -->
<div class="modal fade" id="staticBackdrop{{ $appro->id }}"
    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                        <select class="form-select" name="magasin_id" id="magasinSelect">
                            <option value="">Choisir le magasin </option>
                            @foreach ($magasins as $magasin)
                            <option value="{{ $magasin->id }}"
                                {{ $magasin->id == $appro->magasin_id ? 'selected' : '' }}>
                                {{ $magasin->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Fermer</button>
                    <button type="submit"
                        class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
