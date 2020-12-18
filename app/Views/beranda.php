<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css" integrity="sha512-3g+prZHHfmnvE1HBLwUnVuunaPOob7dpksI7/v6UnF/rnKGwHf/GdEq9K7iEN7qTtW+S0iivTcGpeTBqqB04wA==" crossorigin="anonymous" />">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>


    <title>Hello, world!</title>
</head>

<body>
    <div class="container">
        <h1>&nbsp;</h1>
        <div class="jumbotron">
            <h1 class="display-4">Hello, Document Code!</h1>
            <p class="lead">This is a simple hero unit, a simple multiple upload codeigniter jquery ajax.</p>
            <p class="lead">The file size limit to 2 MB, and files limit to 5</p>


            <form action="<?= base_url('home/process_upload') ?>" class="dropzone"></form>

        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/dropzone.min.js"></script>
    <script>
        var count_file = 0;
        Dropzone.autoDiscover = false;
        var foto = $(".dropzone").dropzone({
            thumbnailWidth: 100,
            thumbnailHeight: 100,
            init: function() {
                myDropzone = this;
                $.ajax({
                    url: '<?= base_url('/load') ?>',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var i = 0;
                        $.each(response['file_list'], function(key, value) {

                            var mockFile = {
                                name: value.name,
                                size: value.size,
                                token: response['database'][i]['token'],
                            };

                            myDropzone.emit("addedfile", mockFile);
                            myDropzone.emit("thumbnail", mockFile, value.path);
                            myDropzone.emit("complete", mockFile);

                            i++;
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

                myDropzone.on('maxfilesreached', function(file) {
                    alert("MAX_FILES_REACHED");
                    myDropzone.removeFile(file);
                })
                myDropzone.on('maxfilesexceeded', function(file) {
                    alert('maxfilesexceeded');
                    myDropzone.removeFile(file);

                })
                myDropzone.on('addedfile', function(file) {
                    count_file += 1;
                    // console.log(myDropzone.files.length);
                    // count_file = myDropzone.getAcceptedFiles().length;
                    if (count_file > 5) {
                        myDropzone.removeFile(file);
                        alert('limit 5 file');
                    }
                    $('.dz-remove').addClass('btn btn-danger mt-2 my-3');
                })

                myDropzone.on('error', function(file, message) {
                    alert(message);
                    myDropzone.removeFile(file);
                })

                myDropzone.on('dragend', function(a) {
                    console.log(token);
                    $.ajax({
                        url: '<?= base_url('home/process_upload') ?>',
                        method: 'post',
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                        }
                    })
                })

                myDropzone.on('sending', function(a, b, c) {
                    a.token = Math.random();
                    c.append('token', a.token);
                })

                myDropzone.on('removedfile', function(a) {
                    count_file -= 1;
                    var token = a.token;
                    $.ajax({
                        url: '<?= base_url() ?>/' + token,
                        method: 'delete',
                        dataType: 'json',
                        success: function(data) {
                            if(data['status']==200){
                                alert('deleted successfully');
                            }
                        }
                    })
                })

                myDropzone.on('thumbnail', function(file, dataUrl) {
                    $('.dz-image').last().find('img').attr({
                        width: '100%',
                        height: '100%'
                    });
                })
            },
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            maxFilesize: 2,
            maxFiles: 5,


        });
        foto.on('addedfile', function() {
            console.log('a');
        })
    </script>
</body>

</html>