// document.addEventListener("DOMContentLoaded", function() {
//     tinymce.init({
//         selector: '#description',
//         skin_url: window.tinymceSkinUrl, // Utiliser la variable définie dans le fichier Blade
//         content_css: window.tinymceContentCss, // Utiliser la variable définie dans le fichier Blade
//         height: 300,
//         menubar: true,
//         plugins: [
//             'advlist autolink lists link image charmap print preview anchor',
//             'searchreplace visualblocks code fullscreen',
//             'insertdatetime media table paste code help wordcount',
//             'fontselect'
//         ],
//         toolbar: 'undo redo | formatselect | fontselect fontsizeselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image media | code help',
//         font_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Times New Roman=times new roman,times,serif;',
//         fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 36pt',
//         content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
//         block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre',
//         font_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Times New Roman=times new roman,times,serif;',
//         style_formats: [
//             { title: 'Heading 1', block: 'h1' },
//             { title: 'Heading 2', block: 'h2' },
//             { title: 'Heading 3', block: 'h3' },
//             { title: 'Heading 4', block: 'h4' },
//             { title: 'Heading 5', block: 'h5' },
//             { title: 'Heading 6', block: 'h6' },
//             { title: 'Paragraph', block: 'p' },
//             { title: 'Preformatted', block: 'pre' },
//             { title: 'Arial', inline: 'span', styles: { 'font-family': 'arial' } },
//             { title: 'Courier New', inline: 'span', styles: { 'font-family': 'courier new' } },
//             { title: 'Times New Roman', inline: 'span', styles: { 'font-family': 'times new roman' } }
//         ]
//     });
// });

//hadhaa nekhdm bihh 
document.addEventListener("DOMContentLoaded", function() {
  tinymce.init({
     selector: '#description',
     height: 400,
     width: '100%',

    // selector: 'textarea',
    plugins: [
      // Core editing features
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      // Your account includes a free trial of TinyMCE premium features
      // Try the most popular premium features until Mar 31, 2025:
      'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
    ],
     toolbar: 'forecolor backcolor  |undo redo | blocks fontfamily fontsize | formatselect | fontselect fontsizeselect | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight alignleft aligncenter alignright alignjustify | checklist numlist bullist indent outdent | emoticons charmap | removeformat  | code help',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
  });
});


