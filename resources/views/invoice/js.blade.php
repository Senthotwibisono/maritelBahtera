<script>
    async function generateLayout(button){
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = {
                id: button.dataset.id
            };

            const url = '{{route('invoice.create')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() => {
                        showLoading();
                        const redirectUrl = `{{ route('invoice.form.index', ['id' => '__ID__']) }}`.replace('__ID__', hasil.data.id);
                        window.location.href = redirectUrl;
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

    async function editInvoiceHeader(button) {
        buttonLoading(button);
        
        const id = button.dataset.id;
        const redirectUrl = `{{ route('invoice.form.index', ['id' => '__ID__']) }}`.replace('__ID__', id);
        window.location.href = redirectUrl;
        
    }

    async function backIndex(button) {
        buttonLoading(button);
        window.location.href = '{{route('invoice.index')}}';
    }

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
                    location.reload();
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
                    location.reload();
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

    async function dataVessel(id) {
        showLoading();
        const data = {
            id
        };
        const url = '{{route('getData.vessel')}}';
        const response = await globalResponse(data, url);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                return hasil;
            }else{
                hideLoading();
                erroHasil(hasil);
                return;

            }
        }else{
            hideLoading();
            errorResponse(response);
            return;
        }
    }

    async function saveAllHeader(button) {
        const result = await confirmation(button);
        if (result.isConfirmed) {
            buttonLoading(button);
            const headerId = document.getElementById('headerId').value;
            const layoutId = document.getElementById('layoutId').value;
            const ves_id = document.getElementById('vessel').value;
            const invoice_date = document.getElementById('invoice_date').value;
            const dwt = document.getElementById('dwt').value;
            const port_of_call = document.getElementById('port').value;
            const grt = document.getElementById('grt').value;
            const nrt = document.getElementById('nrt').value;
            const purpose_of_call = document.getElementById('poc').value;
            const loa = document.getElementById('loa').value;
            const breadth = document.getElementById('breadth').value;
            const activity = document.getElementById('activity').value;
            const country_id = document.getElementById('country').value;
            const cargo = document.getElementById('cargo').value;
            const volume = document.getElementById('volume').value;
            const voy = document.getElementById('voy').value;
            const exchange_rate = document.getElementById('exchange_rate').value;
            const est_port_stay = document.getElementById('est_port_stay').value;
            const idr_amount = document.getElementById('portIDR').value;
            const idr_fund_amount = document.getElementById('fundIDR').value;
            const idr_balance_due = document.getElementById('totalIDR').value;
            const usd_amount = document.getElementById('portUSD').value;
            const usd_fund_amount = document.getElementById('fundUSD').value;
            const usd_balance_due = document.getElementById('totalUSD').value;

            const details = [];
            const inputDetail = document.querySelectorAll('#detilAmount'); 
            inputDetail.forEach((input, index) => {
              details.push({
                mainId: input.dataset.main,
                itemId: input.dataset.item,
                detilId: input.dataset.detil,
                amount: input.value,
              })
            }); 
            const items = [];
            const inputItems = document.querySelectorAll('#itemAmount'); 
            inputItems.forEach((input, index) => {
              items.push({
                mainId: input.dataset.main,
                itemId: input.dataset.item,
                amount: input.value,
              })
            }); 
            const mains = [];
            const inputMains = document.querySelectorAll('#mainAmount'); 
            inputMains.forEach((input, index) => {
              mains.push({
                mainId: input.dataset.main,
                amount: input.value,
              })
            });
            
            const data = {
                headerId,
                layoutId,
                ves_id,
                invoice_date,
                dwt,
                port_of_call,
                grt,
                nrt,
                purpose_of_call,
                loa,
                breadth,
                activity,
                country_id,
                cargo,
                volume,
                voy,
                exchange_rate,
                est_port_stay,
                idr_amount,
                idr_fund_amount,
                idr_balance_due,
                usd_amount,
                usd_fund_amount,
                usd_balance_due,
                details,
                items,
                mains
            };
            const url = `{{ route('invoice.form.submit', ['id' => '__ID__']) }}`.replace('__ID__', headerId);
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil);
                    return;
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

    async function printPDF(button) {
        buttonLoading(button);
        const id = button.dataset.id;
        const url = `{{ route('invoice.print.pdf', ['id' => '__ID__']) }}`.replace('__ID__', id);
        window.open(url, '_blank');
        hideButton(button); 
    }

    async function cancelInvoiceHeader(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = {
                id: button.dataset.id
            };
            const url = '{{route('invoice.cancel')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() => {
                    $('#tableInvoice').DataTable().ajax.reload();
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

    async function reactiveInvoice(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = {
                id: button.dataset.id
            };
            const url = '{{route('invoice.reactive')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() => {
                    $('#tableInvoice').DataTable().ajax.reload();
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
</script>