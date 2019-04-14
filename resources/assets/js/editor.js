import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

class MyUploadAdapter {
    constructor( loader, uploadUrl ) {
        // The file loader instance to use during the upload.
        this.loader = loader;
        this.uploadUrl = uploadUrl;
    }

    // Starts the upload process.
    upload() {
        return this.loader.file
            .then( file => new Promise( ( resolve, reject ) => {
                this._initRequest();
                this._initListeners( resolve, reject, file );
                this._sendRequest( file );
            } ) );
    }

    // Aborts the upload process.
    abort() {
        if ( this.xhr ) {
            this.xhr.abort();
        }
    }

    // Initializes the XMLHttpRequest object using the URL passed to the constructor.
    _initRequest() {
        const xhr = this.xhr = new XMLHttpRequest();

        // Note that your request may look different. It is up to you and your editor
        // integration to choose the right communication channel. This example uses
        // a POST request with JSON as a data structure but your configuration
        // could be different.
        xhr.open( 'POST', this.uploadUrl, true ); // TODO make dynamic
        xhr.responseType = 'json';

        xhr.setRequestHeader('Accept', 'application/json'); 
    }

    // Initializes XMLHttpRequest listeners.
    _initListeners( resolve, reject, file ) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = `Couldn't upload file: ${ file.name }.`;

        xhr.addEventListener( 'error', () => reject( genericErrorText ) );
        xhr.addEventListener( 'abort', () => reject() );
        xhr.addEventListener( 'load', () => {
            const response = xhr.response;

            // This example assumes the XHR server's "response" object will come with
            // an "error" which has its own "message" that can be passed to reject()
            // in the upload promise.
            //
            // Your integration may handle upload errors in a different way so make sure
            // it is done properly. The reject() function must be called when the upload fails.
            if ( !response || response.error ) {
                return reject( response && response.error ? response.error.message : genericErrorText );
            }

            // If the upload is successful, resolve the upload promise with an object containing
            // at least the "default" URL, pointing to the image on the server.
            // This URL will be used to display the image in the content. Learn more in the
            // UploadAdapter#upload documentation.
            resolve( {
                default: response.url
            } );
        } );

        // Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
        // properties which are used e.g. to display the upload progress bar in the editor
        // user interface.
        if ( xhr.upload ) {
            xhr.upload.addEventListener( 'progress', evt => {
                if ( evt.lengthComputable ) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            } );
        }
    }

    // Prepares the data and sends the request.
    _sendRequest( file ) {
        // Prepare the form data.
        const data = new FormData();

        data.append( 'upload', file );

        // Important note: This is the right place to implement security mechanisms
        // like authentication and CSRF protection. For instance, you can use
        // XMLHttpRequest.setRequestHeader() to set the request headers containing
        // the CSRF token generated earlier by your application.

        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            data.append( '_token', token.content );
        }

        // Send the request.
        this.xhr.send( data );
    }
}

// ...

document.querySelectorAll( '#editor' ).forEach((input) => {
    var uploadUrl = input.getAttribute('data-upload-url'); 

    var MyCustomUploadAdapterPlugin = function( editor ) {
        if (uploadUrl) {
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                return new MyUploadAdapter( loader, uploadUrl );
            };
        }
    }

    ClassicEditor
        .create( input, {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo' ],
            mediaEmbed: {
                previewsInData: true,
            },
            extraPlugins: [ MyCustomUploadAdapterPlugin ],
        } )
        .then( editor => {
            // console.log(Array.from( editor.ui.componentFactory.names() ));
        })
        .catch( error => {
            console.error( error );
        } );
});


