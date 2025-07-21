<script>
    // async function submitFile(button) {
    //     // buttonLoading(button);
    //     const result = await confirmation();
    //     if (result.isConfirmed) {
    //         buttonLoading(button);
    //         const fileInput = document.getElementById('file');
    //         const data = new FormData();
    //         data.append('file', fileInput.files[0]);
    //         const url = '{{route('master.port.postFile')}}';
    //         const response = await fetch(url, {
    //             method: "POST",
    //             headers: {
    //               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //             },
    //             body: data
    //         });
    //         hideButton(button);
    //         if (response.ok) {
    //             const hasil = await response.json();
    //             if (hasil.success) {
    //                 successHasil(hasil).then(() => {
    //                     $('#addFile').modal('hide');
    //                     $('#tablePort').DataTable().ajax.reload();
    //                     return;
    //                 })
    //             }else{
    //                 errorHasil(hasil).then(() => {
    //                     return;
    //                 });
    //             }
    //         }else{  
    //             errorResponse(response).then(() => {
    //                 return;
    //             });
    //         }
    //     }else{
    //         hideButton(button);
    //         return;
    //     }
    // }

    async function submitPort(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const name = document.getElementById('name').value;
            const id = document.getElementById('id').value;
            const code = document.getElementById('code').value;
            const data = {
                name,
                id,
                code,
            };
            const url = '{{route('master.country.post')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                console.log(hasil);
                if (hasil.success) {
                    await successHasil(hasil).then(() => {
                        $('#addManual').modal('hide');
                        $('#tableCountry').DataTable().ajax.reload();
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

    async function editCountry(button) {
        buttonLoading(button);
        const id = button.dataset.id;
        const data = {
            id
        };

        const url = '{{route('master.country.edit')}}';
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
</script>