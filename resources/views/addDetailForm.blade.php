@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Detail Form</h2>
    <form id="detailForm" onsubmit="saveDetails(event)" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="number">Mobile</label>
                    <input type="text" class="form-control" id="number" name="number" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

            </div>


            <div class="col-md-5">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

    <hr>


    <h3>Submitted Details</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Password</th>
                <th>Date</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="detailsTableBody">
            <!-- Rows will be added here -->
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-12 text-center">
            <!-- This form will be submitted to save data -->
            <form id="finalSubmitForm" method="POST" action="{{ route('saveDetails') }}">
                @csrf
                <!-- Hidden input where table data will be stored in JSON format -->
                <input type="hidden" name="tableData" id="tableData">
                <button type="button" class="btn btn-success" onclick="finalSubmit()">Final Submit</button>
            </form>
        </div>
    </div>

    <div class="container mt-4">
        <!-- Bulk Upload Form -->
        <form action="{{ route('details.bulkUpload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="excel_file">Upload Excel File</label>
                <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Bulk Upload</button>
        </form>

        <!-- Bulk Download Button -->
        <form action="{{ route('details.bulkDownload') }}" method="GET">
            <button type="submit" class="btn btn-success mt-4">Download Excel</button>
        </form>
    </div>

</div>

<!-- JavaScript function to save form data in table -->
<script>
    function saveDetails(event) {
        event.preventDefault();

        // Get form values
        let name = document.getElementById('name').value;
        let email = document.getElementById('email').value;
        let number = document.getElementById('number').value;
        let role = document.getElementById('role').value;
        let password = document.getElementById('password').value;
        let date = document.getElementById('date').value;
        let imageFile = document.getElementById('image').files[0];

        // Create a new row for the table
        let tableBody = document.getElementById('detailsTableBody');
        let newRow = tableBody.insertRow();

        // Add cells to the new row
        newRow.insertCell(0).textContent = name;
        newRow.insertCell(1).textContent = email;
        newRow.insertCell(2).textContent = number;
        newRow.insertCell(3).textContent = role;
        newRow.insertCell(4).textContent = password;
        newRow.insertCell(5).textContent = date;

        // Handle image display in the table
        let imageCell = newRow.insertCell(6);
        if (imageFile) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.width = 50;
                imageCell.appendChild(img);
            }
            reader.readAsDataURL(imageFile);
        } else {
            imageCell.textContent = 'No Image';
        }

        // Add action buttons
        let actionsCell = newRow.insertCell(7);
        let editButton = document.createElement('button');
        editButton.textContent = 'Edit';
        editButton.className = 'btn btn-warning btn-sm';
        editButton.onclick = function() {
            editRow(newRow);
        };

        let deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.className = 'btn btn-danger btn-sm';
        deleteButton.onclick = function() {
            deleteRow(newRow);
        };

        actionsCell.appendChild(editButton);
        actionsCell.appendChild(deleteButton);

        // Clear form fields after adding
        document.getElementById('detailForm').reset();
    }

    function editRow(row) {
        let name = row.cells[0].textContent;
        let email = row.cells[1].textContent;
        let number = row.cells[2].textContent;
        let role = row.cells[3].textContent;
        let password = row.cells[4].textContent;
        let date = row.cells[5].textContent;

        document.getElementById('name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('number').value = number;
        document.getElementById('role').value = role;
        document.getElementById('password').value = password;
        document.getElementById('date').value = date;

        row.remove();
    }

    function deleteRow(row) {
        row.remove();
    }

    function finalSubmit() {
        let tableBody = document.getElementById('detailsTableBody');
        if (tableBody.rows.length === 0) {
            alert("No data to submit!");
            return;
        }

        let tableData = [];
        let formData = new FormData();

        for (let i = 0; i < tableBody.rows.length; i++) {
            let row = tableBody.rows[i];
            let rowData = {
                name: row.cells[0].textContent,
                email: row.cells[1].textContent,
                number: row.cells[2].textContent,
                role: row.cells[3].textContent,
                password: row.cells[4].textContent,
                date: row.cells[5].textContent,

            };
            tableData.push(rowData);
        }

        formData.append('tableData', JSON.stringify(tableData));


        let imageInput = document.getElementById('image');
        if (imageInput.files.length > 0) {
            console.log("here");
            formData.append('image', imageInput.files[0]);
        }
        console.log(formData);
        // Submit the form with the image
        fetch('{{ route("saveDetails") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ensure CSRF token is sent
                },
            })
            .then(response => response.json())
            .then(data => {
                // Handle success or error
                if (data.success) {
                    alert(data.message);
                    // Reset the table and form here if needed
                }else{
                    console.log("error here");
                }
            })
            // .catch(error => {
            //     console.error('Error:', error);
            // })
            ;
    }
</script>
@endsection