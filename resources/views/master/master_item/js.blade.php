<script>
    async function submitItem(button) {
        const result = await confirmation();
        console.log("Remark saat klik submit:", document.getElementById('remark').value);

        if (result.isConfirmed) {
            buttonLoading(button);
            const name = document.getElementById('name').value;
            const id = document.getElementById('id').value;
            const tarif_dasar = document.getElementById('tarif_dasar').value;
            const unit = document.getElementById('unit').value;
            const remark = document.getElementById('remark').value;
            const formula = document.getElementById('formula').value;
            const data = {
                name,
                id,
                tarif_dasar,
                unit,
                remark,
                formula,
            };

            const url = '{{route('master.item.post')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    await successHasil(hasil).then(() => {
                        $('#addManual').modal('hide');
                        $('#tableItem').DataTable().ajax.reload();
                    });
                }else{
                    errorHasil(hasil);
                }
            }else{
                errorReponse(response);
                return;
            }
        }else{
            return;
        }
    }

    async function editItem(button) {
        buttonLoading(button);
        const id = button.dataset.id;
        const data = {
            id
        };
        const url = '{{route('master.item.edit')}}';
        const response = await globalResponse(data, url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                await successHasil(hasil).then(() => {
                    $('#addManual #name').val(hasil.data.name);
                    $('#addManual #id').val(hasil.data.id);
                    $('#addManual #tarif_dasar').val(hasil.data.tarif_dasar);
                    $('#addManual #unit').val(hasil.data.unit);
                    $('#addManual #remark').val(hasil.data.remark);
                    $('#addManual #formula').val(hasil.data.formula);
                    checkFields();
                    $('#addManual').modal('show');
                }); 
                return;
            }else{
                errorHasil(hasil);
                return;
            }
        }else{
            errorResponse(response);
            return;
        }
    }

    async function deleteItem(button) {
        buttonLoading(button);
        const id = button.dataset.id;
        const data = {
            id
        };
        const url = '{{route('master.item.delete')}}';
        const response = await globalResponse(data, url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                await successHasil(hasil).then(() => {
                    $('#tableItem').DataTable().ajax.reload();
                }); 
                return;
            }else{
                errorHasil(hasil);
                return;
            }
        }else{
            errorResponse(response);
            return;
        }
    }

    async function submitVariable(button) {
        const result  = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const key = document.getElementById('key').value;
            const label = document.getElementById('label').value;
            const source_table = document.getElementById('source_table').value;
            const source_field = document.getElementById('source_field').value;
            const description = document.getElementById('desc').value;
            const data = {
                key,
                label,
                source_table,
                source_field,
                description
            };

            const url = '{{route('master.variable.post')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() => {
                        $('#addVariableModal').modal('hide');
                        loadFormulaButtons();
                    });
                    return;
                }else{  
                    errorHasil(hasil);
                }
            }else{
                errorResponse(response);
            }
        }else{
            return;
        }
    }

    function loadFormulaButtons() {
    $.get('{{ route('master.variable.get') }}', function (variables) {
        const container = $('#selectFormula');
        container.empty(); // kosongkan dulu

        // Buat wrapper flex
        const row = $('<div class="d-flex flex-wrap gap-2 mb-3"></div>');

        // Tambahkan setiap button ke dalam wrapper
        variables.forEach(variable => {
            const button = `
                <button type="button" class="btn btn-outline-secondary" onclick="addToFormula('${variable.key}')">${variable.label}</button>
            `;
            row.append(button);
        });

        container.append(row);

        // Tambah tombol 'Add New Variable' di akhir
        container.append(`
            <div>
                <button type="button" class="btn btn-outline-primary" onclick="addVariable(this)">Add New Variable</button>
            </div>
        `);
    });
}

</script>