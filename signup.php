<?php
include 'admin/db_connect.php';
?>
<style>
    .masthead {
        min-height: 23vh !important;
        height: 23vh !important;
    }

    .masthead:before {
        min-height: 23vh !important;
        height: 23vh !important;
    }

    img#cimg {
        max-height: 10vh;
        max-width: 6vw;
    }
</style>
<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Create Account</h3>
                <hr class="divider my-4" />

                <div class="col-md-12 mb-2 justify-content-center">
                </div>
            </div>

        </div>
    </div>
</header>
<div class="container mt-3 pt-2">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="col-md-12">
                        <form action="" id="create_account">
                            <div class="row form-group">
                                <div class="col-md-4" id='l_name'>
                                    <label for="" class="control-label">Last Name</label>
                                    <input type="text" class="form-control" name="lastname">
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="control-label">Name</label>
                                    <input type="text" class="form-control" name="firstname" required>
                                </div>
                                <div class="col-md-4" id="m_name">
                                    <label for="" class="control-label">Middle Name</label>
                                    <input type="text" class="form-control" name="middlename">
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="control-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="control-label">Phone</label>
                                    <input type="text" class="form-control" name="phone">
                                </div>
                            </div>
                            <div class="row form-group">
                                <!-- <div class="col-md-4">
                                    <label for="" class="control-label">Gender</label>
                                    <select class="custom-select" name="gender" required>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div> -->

                                <div class="col-md-4">
                                    <label for="" class="control-label">User Type</label>
                                    <select class="custom-select" id="user_type" name="user_type" required>
                                        <option></option>
                                        <option value="10">Student</option>
                                        <option value="3">Alumni</option>
                                        <option value="20">Organization</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="o_type">
                                    <label for="" class="control-label">Org Type</label>
                                    <select class="custom-select" name="org_type">
                                        <option></option>
                                        <option value="1">Academia</option>
                                        <option value="2">Industry</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="academia">
                                    <label for="" class="control-label">Institute</label>
                                    <!-- onchange="loadDepartments()" -->
                                    <select class="custom-select select2" id="academia_id" name="academia_id" onchange="loadDepartments()">
                                        <option></option>
                                        <?php
                                        $academia = $conn->query("SELECT * FROM orgnaization_bio order by name asc");
                                        while ($row =  $academia->fetch_assoc()) :
                                        ?>
                                            <option value="<?php echo $row['org_id'] ?>"><?php echo $row['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-4" id="dept_id">
                                    <label for="" class="control-label">Department</label>
                                    <select class="custom-select select2" name="dept_id" id="dept">
                                        <option></option>

                                    </select>
                                </div>


                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="" class="control-label">Address</label>
                                    <input type="text" class="form-control" name="address" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4" id="gender">
                                    <label for="" class="control-label">Gender</label>
                                    <select class="custom-select" name="gender" required>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="batch">
                                    <label for="" class="control-label">Batch</label>
                                    <input type="input" class="form-control datepickerY" name="batch">
                                </div>
                                <div class="col-md-4" id="course_id">
                                    <label for="" class="control-label">Course Graduated</label>
                                    <select class="custom-select select2" name="course_id">
                                        <option></option>
                                        <?php
                                        $course = $conn->query("SELECT * FROM courses order by course asc");
                                        while ($row = $course->fetch_assoc()) :
                                        ?>
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['course'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-5" id="works_at">
                                    <label for="" class="control-label">Currently Works As</label>
                                    <textarea name="connected_to" id="" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="col-md-5">
                                    <label for="" class="control-label">Image</label>
                                    <input type="file" class="form-control" name="img" onchange="displayImg(this,$(this))">
                                    <img src="" alt="" id="cimg">

                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-md-4">
                                    <label for="" class="control-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div> -->
                                <div class="col-md-4">
                                    <label for="" class="control-label">Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="control-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                            </div>
                            <div id="msg">

                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary">Create Account</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    $('.datepickerY').datepicker({
        format: " yyyy",
        viewMode: "years",
        minViewMode: "years"
    })
    $('.select2').select2({
        placeholder: "Please Select Here",
        width: "100%"
    })

    $('#l_name').hide();
    $('#m_name').hide();
    $('#gender').hide();
    $('#batch').hide();
    $('#course_id').hide();
    $('#works_at').hide();
    $('#o_type').hide();
    // code start
    // Attach the change event handler to the dropdown element
    $("#user_type").change(function() {
        // This function will be executed when the selected option changes
        var selectedOption = $(this).val(); // Get the selected value
        console.log("Selected option: " + selectedOption);

        if (selectedOption == "20") { // organization
            $('#o_type').show();
            $('#batch').hide();
            $('#gender').hide();
            $('#course_id').hide();
            $('#works_at').hide();
            $('#academia').hide();
            $('#dept_id').hide();
            // $("#gender").hide();
            // $("#birthday").hide();
            // $("#institute_name").hide();
            // $("#alumni_job").hide();
            // $("#org_type").hide();
        } else if (selectedOption == "3") { //Alumni
            $("#gender").show();
            $("#birthday").show();
            $("#institute_name").show();
            $("#alumni_job").show();
            $("#institute_name").show();
            $('#works_at').show();
            $('#batch').show();
        } else {
            $("#gender").show();
            $("#birthday").show();
            $("#institute_name").show();
            $("#alumni_job").hide();
            $("#org_type").show();
            $("#institute_name").show();
            $('#batch').show();
            $('#works_at').show();
        }

    });

    // load departments
    function loadDepartments() {
        var universityId = document.getElementById('academia_id').value;
        console.log(universityId);
        // var departmentSelect = document.getElementById('department');

        // // Reset department options
        // departmentSelect.innerHTML = '<option value="default">Select an option</option>';
        // start_load()
        $.ajax({
            url: 'admin/ajax.php?action=load_depts',
            data: {
                id: universityId
            },
            // cache: false,
            // contentType: false,
            // processData: false,
            method: 'POST',
            // type: 'POST',
            // dataType: 'json',
            success: function(resp) {
                console.log("OBJECT: ", resp);

                var r = JSON.parse(resp);
                // console.log("r", r[0][1]);
                var selectElement = document.getElementById('dept');
                selectElement.innerHTML = '';
                // selectElement = '';
                // $("#dept") = '';
                if (r.length > 0) {
                    for (let i = 0; i < r.length; i++) {
                        // var option = document.createElement('option');
                        // Set the value and text of the option
                        // option.value = r[i][0];
                        // option.text = r[i][1];
                        // Append the option to the select element
                        // selectElement.add(option);
                        $("#dept").append('<option value="' + r[i][0] + '">' + r[i][1] + ' </option>');
                        // console.log("id" + r[i][0])
                    }
                }
                // if (r) {
                //     r.forEach((element) => console.log(element));
                // }
                // for (const key in r) {
                //     if (r.hasOwnProperty(key)) {
                //         console.log(`${key}: ${r[key]}`);
                //     }
                // }

                // if (r) {
                //     console.log("R IS: ", r)
                //     r.forEach((item) => {
                //         console.log("ITEM IS: ", item)
                //     })
                // }
                // try {
                //     var r = await JSON.parse(resp);
                //     const results = resp?.map((item) => {
                //         return (
                //             console.log("ITEM IS: ", item)
                //         )
                //     })
                // } catch (error) {
                //     console.error("ERROR IS: ", error.message)
                // }

                // console.log('Hello resp' + r.data)
                // for (let i = 0; i < resp.length; i++) {
                //     console.log("hello" + resp.name)
                // }
                // if (resp == 1) {
                //     location.replace('index.php')
                // } else if (resp == 2) { // organization registered successfully.
                //     location.replace('index.php')
                // } else if (resp == 5) {
                //     $('#msg').html('<div class="alert alert-danger">Password and confirm password does not match.</div>')
                //     end_load()
                // } else {
                //     $('#msg').html('<div class="alert alert-danger">email already exist.</div>')
                //     end_load()
                // }
            }
        })
        // if (universityId !== 'default') {
        //     // Fetch departments using AJAX (Assuming you have a PHP script to handle this)
        //     var xhr = new XMLHttpRequest();
        //     xhr.open('GET', 'get_departments.php?university_id=' + universityId, true);
        //     xhr.onload = function() {
        //         if (xhr.status === 200) {
        //             var departments = JSON.parse(xhr.responseText);
        //             departments.forEach(function(department) {
        //                 var option = document.createElement('option');
        //                 option.value = department.id;
        //                 option.text = department.name;
        //                 departmentSelect.appendChild(option);
        //             });
        //         }
        //     };
        //     xhr.send();
        // }
    }
    // my code end

    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#cimg').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $('#create_account').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'admin/ajax.php?action=signup',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                console.log('Hello resp' + resp)
                if (resp == 1) {
                    location.replace('index.php')
                } else if (resp == 2) { // organization registered successfully.
                    location.replace('index.php?page=org_dash')
                } else if (resp == 5) {
                    $('#msg').html('<div class="alert alert-danger">Password and confirm password does not match.</div>')
                    end_load()
                } else if (resp == 10) {
                    console.log("Student Signup")
                } else {
                    // testing
                    // $('#msg').html('<div class="alert alert-danger">email already exist.</div>')
                    $('#msg').html('<div class="alert alert-danger">' + resp + 'email already exist.</div>')
                    end_load()
                }
            }
        })
    })
</script>