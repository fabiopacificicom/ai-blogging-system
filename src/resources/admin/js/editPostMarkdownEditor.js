
import Editor from '@toast-ui/editor'
/* import 'codemirror/lib/codemirror.css'; */
import '~resources/scss/blog/toastui-editor.css'
import '~resources/scss/blog/toastui-editor-dark.css'

/* Create the Summary Editor */
const editorSummary = new Editor({
    el: document.querySelector('#editor_summary'),
    height: '400px',
    initialEditType: 'markdown',
    placeholder: 'Write something cool!',
    theme: document.querySelector('html').getAttribute('data-bs-theme')

})

/* Handle Summary form submission */

if (document.querySelector('#summaryEditor')) {
    editorSummary.setMarkdown(document.querySelector('#oldSummary').value);

    document.querySelector('#summaryEditor').addEventListener('submit', e => {
        e.preventDefault();
        document.querySelector('#summary').value = editorSummary.getMarkdown();
        e.target.submit();
    });
}


/* Create the Content Editor */

const editorContent = new Editor({
    el: document.querySelector('#editor_content'),
    height: '400px',
    initialEditType: 'markdown',
    placeholder: 'Write something cool!',
    theme: document.querySelector('html').getAttribute('data-bs-theme')
})

/* Handle Content form submission */

if (document.querySelector('#contentEditor')) {
    editorContent.setMarkdown(document.querySelector('#oldContent').value);

    document.querySelector('#contentEditor').addEventListener('submit', e => {
        e.preventDefault();
        document.querySelector('#content').value = editorContent.getMarkdown();
        e.target.submit();
    });
}

