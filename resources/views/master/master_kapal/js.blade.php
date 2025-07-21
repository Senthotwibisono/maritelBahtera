<script>
    async function submitFile(button) {
        // buttonLoading(button);
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const fileInput = document.getElementById('file');
            const data = new FormData();
            data.append('file', fileInput.files[0]);
            const url = '{{route('master.port.postFile')}}';
            const response = await fetch(url, {
                method: "POST",
                headers: {
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: data
            });
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    successHasil(hasil).then(() => {
                        $('#addFile').modal('hide');
                        $('#tablePort').DataTable().ajax.reload();
                        return;
                    })
                }else{
                    errorHasil(hasil).then(() => {
                        return;
                    });
                }
            }else{  
                errorResponse(response).then(() => {
                    return;
                });
            }
        }else{
            hideButton(button);
            return;
        }
    }

    async function submitVessel(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const name = document.getElementById('name').value;
            const id = document.getElementById('id').value;
            const code = document.getElementById('code').value;
            const dwt = document.getElementById('dwt').value;
            const grt = document.getElementById('grt').value;
            const nrt = document.getElementById('nrt').value;
            const loa = document.getElementById('loa').value;
            const breadth = document.getElementById('breadth').value;
            const owner = document.getElementById('owner').value;
            const country = document.getElementById('country').value;
            const data = {
                name,
                id,
                code,
                dwt,
                grt,
                nrt,
                loa,
                breadth,
                owner,
                country,
            };
            const url = '{{route('master.vessel.post')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                console.log(hasil);
                if (hasil.success) {
                    await successHasil(hasil).then(() => {
                        $('#addManual').modal('hide');
                        $('#tableKapal').DataTable().ajax.reload();
                        return;
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

    async function editVessel(button) {
        buttonLoading(button);
        const id = button.dataset.id;
        const data = {
            id
        };

        const url = '{{route('master.vessel.edit')}}';
        const response = await globalResponse(data, url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                await successHasil(hasil).then(() => {
                    $('#addManual').modal('show');
                    $('#addManual #name').val(hasil.data.name);
                    $('#addManual #id').val(hasil.data.id);
                    $('#addManual #code').val(hasil.data.code);
                    $('#addManual #desc').val(hasil.data.description);
                    $('#addManual #country').val(hasil.data.country_id).trigger('change');
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

    async function deleteVessel(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const id = button.dataset.id;
            const data = {
                id
            };
            const url = '{{route('master.vessel.delete')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    await successHasil(hasil).then(() => {
                        $('#tableKapal').DataTable().ajax.reload();
                        return;
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

