<?php
    $uploaded_filenames = '';
    if ($_FILES['files']['name'] && $_FILES['files']['name'][0]) {
        $uploaded_filenames = 'You just uploaded these files: <ul><li>'.implode('</li><li>', $_FILES['files']['name']).'</li></ul>';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
    .box {
        background-color: white;
        outline: 2px dashed black;
        height: 400px;
    }
    .box.is-dragover {
        background-color: grey;
    }

    .box {
        display:flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .box label strong {
        text-decoration: underline;
        color: blue;
        cursor: pointer;
    }

    .box label strong:hover {
        color: blueviolet
    }

    .box input {
        display: none;
    }
</style>
</head>
<body>
    <?php echo $uploaded_filenames; ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
        <div class="box">
            <label>
                <strong>Choose files</strong>
                <span>or drag them here.</span>
                <input class="box__file" type="file" name="files[]" multiple />
            </label>
            <div class="file-list"></div>
        </div>
        <button>Submit</button>
    </form>

    <script>

        const box = document.querySelector('.box');
        const fileInput = document.querySelector('[name="files[]"');
        const selectButton = document.querySelector('label strong');
        const fileList = document.querySelector('.file-list');

        let droppedFiles = [];

        [ 'drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop' ].forEach( event => box.addEventListener(event, function(e) {
            e.preventDefault();
            e.stopPropagation();
        }), false );

        [ 'dragover', 'dragenter' ].forEach( event => box.addEventListener(event, function(e) {
            box.classList.add('is-dragover');
        }), false );

        [ 'dragleave', 'dragend', 'drop' ].forEach( event => box.addEventListener(event, function(e) {
            box.classList.remove('is-dragover');
        }), false );

        box.addEventListener('drop', function(e) {
            droppedFiles = e.dataTransfer.files;
            fileInput.files = droppedFiles;
            updateFileList();
        }, false );

        fileInput.addEventListener( 'change', updateFileList );

        function updateFileList() {
            const filesArray = Array.from(fileInput.files);
            if (filesArray.length > 1) {
                fileList.innerHTML = '<p>Selected files:</p><ul><li>' + filesArray.map(f => f.name).join('</li><li>') + '</li></ul>';
            } else if (filesArray.length == 1) {
                fileList.innerHTML = `<p>Selected file: ${filesArray[0].name}</p>`;
            } else {
                fileList.innerHTML = '';
            }
        }

    </script>
</body>
</html>