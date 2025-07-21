<script>
    function createUser(button) {
        buttonLoading(button);
        $('#userModal #name').val(null);
        $('#userModal #id').val(null);
        $('#userModal #email').val(null);
        $('#userModal #role').val(null).trigger('change');
        $('#userModal').modal('show');
        hideButton(button);
    }
    async function editUser(button) {
       buttonLoading(button);
        const id = button.dataset.id;
        const url = "{{route('user.data')}}";
        const response = await fetch(url,{
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": "{{ csrf_token() }}",
              "Content-Type": "application/json",
            },
            body: JSON.stringify({id}),
        });
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                await successHasil(hasil).then(() => {
                    $('#userModal').modal('show');
                    $('#userModal #name').val(hasil.data.user.name);
                    $('#userModal #id').val(hasil.data.user.id);
                    $('#userModal #email').val(hasil.data.user.email);
                    $('#userModal #role').val(hasil.data.roles.name).trigger('change');
                });
            }else{
                await errorHasil(hasil).then(() => {
                    return;
                });
            }
        }else{
            await errorResponse(response).then(() => {
                hideButton(button);
                return;
            });
        }
    }

    async function submitUserForm(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
           const name = document.getElementById('name').value;
           const id = document.getElementById('id').value;
           const email = document.getElementById('email').value;
           const role = document.getElementById('role').value;
           const password = document.getElementById('password').value;
           const data = {
                name,
                id,
                email,
                role,
                password,
           };
           const url = '{{route('user.store')}}';
           const response = await globalResponse(data, url);
           console.log(response);
           hideButton(button);
           if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                await successHasil(hasil).then(() => {
                    $('#userModal').modal('hide');
                    $('#tableUser').DataTable().ajax.reload();
                });
                return;
            }else{
                errorHasil(hasil);
                return;
            }
           }else{
            await errorResponse(response);
           }
        } else {
            return;
        }
    }

    async function deleteUser(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const data = {
                id : button.dataset.id
            };
            const url = '{{route('user.delete')}}';
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                await successHasil(hasil).then(() => {
                    $('#tableUser').DataTable().ajax.reload();
                });
                return;
            }else{
                errorHasil(hasil);
                return;
            }
            }else{
             await errorResponse(response);
            }
        }else{
            hideButton(button);
         return;
        }
    }
</script>