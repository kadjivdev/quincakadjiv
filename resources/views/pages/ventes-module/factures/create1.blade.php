<script>
    var apiUrl = "{{ config('app.url_ajax') }}";

    // ... (rest of your existing Javascript code)

    function populateUnitOptions(articleId, selectElement) {
        $.ajax({
            url: apiUrl + '/unites/' + articleId,
            type: 'GET',
            success: function(data) {
                selectElement.empty(); // Clear existing options

                if (data.unites.length > 0) {
                    selectElement.append('<option value="">Choisir l\'unité</option>');

                    for (const unite of data.unites) {
                        const option = `<option value="${unite.id}">${unite.libelle}</option>`;
                        selectElement.append(option);
                    }
                } else {
                    selectElement.append('<option value="">Aucune unité disponible</option>');
                }
            },
            error: function(error) {
                console.log('Erreur de la requête AJAX pour les unités:', error);
            }
        });
    }

    $('#editableTable tbody').on('change', 'select[name^="unite_id"]', function() {
        calculateTotal();
    });

    $('#devisSelect').change(function() {
        var devisId = $(this).val();
        $.ajax({
            url: apiUrl + '/lignesDevis/' + devisId,
            type: 'GET',
            success: function(data) {
                $('#editableTable tbody').empty();

                if (data.articles.length > 0) {
                    $("#clientNom").val(data.articles[0].nom_client);
                    $("#seuil").val(data.articles[0].seuil);

                    for (const article of data.articles) {
                        const newRow = `
                            <tr>
                                <td>${article.nom}
                                    <input type="hidden" name="article[]" readonly value="${article.article_id}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="qte_cmde[]" value="${article.qte_cmde}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="prix_unit[]" value="${article.prix_unit}" class="form-control">
                                </td>
                                <td>
                                    <select name="unite_id[]" class="form-control unit-select"></select>
                                </td>
                                <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
                            </tr>`;

                        const newRowElement = $(newRow);
                        const unitSelect = newRowElement.find('.unit-select');
                        populateUnitOptions(article.article_id, unitSelect); // Populate unit options based on article_id

                        $('#editableTable tbody').append(newRowElement);
                    }

                    calculateTotal();
                }

                $('.delete-row').click(function() {
                    $(this).closest('tr').remove();
                    calculateTotal();
                });
            },
            error: function(error) {
                console.log('Erreur de la requête AJAX:', error);
            }
        });
    });

    // Trigger initial change event for selected devis
    var defaultDevisId = $('#devisSelect').val();
    $('#devisSelect').trigger('change');
</script>
