<script>

    async function addMain(button) {
        buttonLoading(button);
        $('#addMainModal #mainName').val(null);
        $('#addMainModal #mainId').val(null);
        $('#addMainModal #vatMain').val(0);
        $('#addMainModal #notaItMain').prop('checked', false);
        $('#addMainModal #notaAdminMain').prop('checked', false);
        $('#addMainModal').modal('show');
        hideButton(button);
    }

    async function submitMain(button) {
        buttonLoading(button);
        const layoutId = {{$layout->id}};
        const name = document.getElementById('mainName').value;
        const mainId = document.getElementById('mainId').value;
        const currency_flag = document.getElementById('currency_flag').value;
        const vat = document.getElementById('vatMain').value;
        const notaIt = document.getElementById('notaItMain').checked;
        const notaAdmin = document.getElementById('notaAdminMain').checked;
        const data = {
            layoutId:layoutId,
            name,
            mainId,
            currency_flag,
            vat,
            notaIt,
            notaAdmin
        };
        const url = '{{route('master.layout.postLayoutMain')}}'
        const response = await globalResponse(data,url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                await successHasil(hasil).then(() => {
                    showLoading();
                    const url = `{{ route('master.layout.contentDetil', ['id' => '__id__']) }}`.replace('__id__', layoutId);
                    $("#contentLayout").load(url);
                    $('#addMainModal').modal('hide');
                    
                    hideLoading();
                    initMainDrag();
                    initDetilDrag();
                });
                return;
            }else{
                errorHasil(hasil);
            }
        }else{
            errorResponse(response);
            return;
        }
    } 

    async function editMain(button) {
        buttonLoading(button);
        const mainId = button.dataset.id;
        const data = {
            mainId
        };
        const url = '{{route('master.layout.editLayoutMain')}}';

        const response = await globalResponse(data, url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#addMainModal #mainName').val(hasil.data.name);
                $('#addMainModal #mainId').val(hasil.data.id);
                $('#addMainModal #vatMain').val(hasil.data.vat);
                if (hasil.data.admin_nota == 'Y') {
                    $('#addMainModal #notaAdminMain').prop('checked', true);
                } else {
                    $('#addMainModal #notaAdminMain').prop('checked', false);
                }

                if (hasil.data.admin_it == 'Y') {
                    $('#addMainModal #notaItMain').prop('checked', true);
                } else {
                    $('#addMainModal #notaItMain').prop('checked', false);
                }
                $('#addMainModal').modal('show');
            }else{
                errorHasil(hasil);
            }
        }else{
            errorResponse(response);
            return;
        }

    }

    async function deleteMain(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);

            const data = {
                mainId: button.dataset.id,
                layoutId: {{$layout->id}}
            };

            const url = '{{route('master.layout.deleteLayoutMain')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    showLoading();
                    const layoutId = {{$layout->id}};
                    const url = `{{ route('master.layout.contentDetil', ['id' => '__id__']) }}`.replace('__id__', layoutId);
                    $("#contentLayout").load(url);
                    hideLoading();
                    initMainDrag();
                    initDetilDrag();
                }else{
                    errorHasil(hasil);
                    return;
                }
            }else{
                errorResponse(response);
            }
        }else{  
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

    async function submitItem(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const mainId = document.querySelector('#addItemModal #mainId').value;
            const itemId = document.querySelector('#addItemModal #itemId').value;
            const mitem = document.querySelector('#addItemModal #mitem').value;
            const unitItem = document.querySelector('#addItemModal #unitItem').value;
            const formulaItem = document.querySelector('#addItemModal #formulaItem').value;
            const remarkItem = document.querySelector('#addItemModal #remarkItem').value;
            const layoutId = {{$layout->id}};
            const data = {
                mainId,
                itemId,
                mitem,
                unitItem,
                formulaItem,
                remarkItem,
                layoutId,
            };

            const url = '{{route('master.layout.submitItem')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    await successHasil(hasil).then(() => {
                        showLoading();
                        window.location.reload();
                    })
                }else{
                    errorHasil(hasil);
                }
            }else{
                errorResponse(response);
                return;
            }
        }else{
            return;
        }
    }

    async function deleteLayoutItem(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = {
                layoutId: {{$layout->id}},
                mainId: button.dataset.main,
                id: button.dataset.id
            }
            const url = '{{route('master.layout.deleteItem')}}';
            const resposne = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() => {
                        showLoading();
                        location.reload();
                    });
                }else{
                    errorHasil(hasil);
                    return;
                }
            }else{
                errorResponse(response);
                return;
            }
        }else{
            return;
        }
    }
    async function saveDetilAmount(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = [];   
            const inputs = document.querySelectorAll('#detilAmount'); 
            inputs.forEach((input, index) => {
              data.push({
                layoutId: {{$layout->id}},
                mainId: input.dataset.main,
                itemId: input.dataset.item,
                detilId: input.dataset.detil,
                amount: input.value,
              })
            }); 
            console.log(data);
            const url = '{{route('master.layout.updateDetilAmount')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() => {
                        showLoading();
                        location.reload();
                    })
                }else{
                    errorHasil(hasil);
                    return;
                }
            }else{
                errorResponse(response);
                return;
            }
        }else{
            return;
        }
    }
    
    async function backIndex(button) {
        buttonLoading(button);
        window.location.href = '{{route('master.layout.index')}}';
    }

    async function deleteLayout(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = {
                id: {{$layout->id}}
            };
            const url = '{{route('master.layout.delete')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() => {
                        showLoading();
                        window.location.href = '{{route('master.layout.index')}}';
                    })
                }else{
                    errorHasil(hasil);
                    return;
                }
            }else{
                errorResponse(response);
                return;
            }
        }else{
            return;
        }

    }

</script>