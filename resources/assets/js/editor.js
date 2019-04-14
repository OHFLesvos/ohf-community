import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

ClassicEditor
    .create( document.querySelector( '#editor' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList' /*, 'imageUpload'*/, 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo' ],
        mediaEmbed: {
            previewsInData: true,
        }
    } )
    .then( editor => {
        // console.log(Array.from( editor.ui.componentFactory.names() ));
    })
    .catch( error => {
        console.error( error );
    } );
