import Editor from '@toast-ui/editor'
/* import 'codemirror/lib/codemirror.css'; */
import '~resources/scss/blog/toastui-editor.css'
import '~resources/scss/blog/toastui-editor-dark.css'

/* Create the Summary Editor */
const editorSummary = new Editor({
    el: document.querySelector('#editor_summary'),
    height: '400px',
    initialEditType: 'markdown',
    placeholder: '',
    theme: document.querySelector('html').getAttribute('data-bs-theme')

})
/* Create the Content Editor*/

const editorContent = new Editor({
    el: document.querySelector('#editor_content'),
    height: '400px',
    initialEditType: 'markdown',
    placeholder: '',
    theme: document.querySelector('html').getAttribute('data-bs-theme')
})

/* Handle Summary form submission */

if (document.querySelector('#createPostForm')) {
    editorSummary.setMarkdown(document.querySelector('#oldSummary').value);
    editorContent.setMarkdown(document.querySelector('#oldContent').value);

    document.querySelector('#createPostForm').addEventListener('submit', e => {
        e.preventDefault();
        document.querySelector('#summary').value = editorSummary.getMarkdown();
        document.querySelector('#content').value = editorContent.getMarkdown();
        e.target.submit();
    });
}


