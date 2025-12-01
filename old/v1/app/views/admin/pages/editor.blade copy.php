<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Code editor app</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type='text/javascript' src='{{ vendor('plugins/win/jQueryFileExplorer.js') }}'></script>
  <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/split.js/1.6.2/split.min.js'></script>
  <link rel='stylesheet' href='{{ vendor('plugins/win/jQueryFileExplorer.css') }}' type='text/css' />

  <!-- CodeMirror CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/darcula.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/monokai.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/midnight.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/material-ocean.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/the-matrix.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/ambiance.min.css">
  <!-- CodeMirror Addon CSS for dialog and matchesonscrollbar -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/dialog/dialog.min.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/matchesonscrollbar.min.css">
  <style>
    html,
    * {
      box-sizing: border-box;
    }

    body {
      background-color: #fafafa;
      line-height: 1.6;
    }

    .lead {
      font-size: 1.5rem;
      font-weight: 300;
    }

    .container {
      max-width: 960px;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .editor-container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 24px;
    }

    .theme-switcher {
      margin-bottom: 16px;
    }

    .CodeMirror {
      height: 400px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
  </style>
  <script>
    $(function () {
      $("#fileexplorer1").jQueryFileExplorer({
        root: "/",
        rootLabel: "Server",
        script: getPath,
        fileScript: function (file) { window.open("https://localhost:44316/FileExplorer/GetPath?path=" + file.path); }
      });

    });

    function getPath(data) {
      if (data.path == '/') {
        return [
          {
            label: 'A:',
            path: 'A:',
            isDrive: true,
            isFolder: true,
            hasSubfolder: true,
            subitems: ['Total: 300,000', 'Free: 100,000']
          },
          {
            label: 'C:',
            path: 'C:',
            isDrive: true,
            isFolder: true,
            hasSubfolder: true,
            subitems: ['Total: 300,000', 'Free: 100,000']
          },
          {
            label: 'D:',
            path: 'D:',
            isDrive: true,
            isFolder: true,
            hasSubfolder: true,
            subitems: ['Total: 100,000', 'Free: 80,000']
          }
        ];
      }
      else if (data.path == 'C:') {
        return [
          {
            label: 'Windows',
            path: 'C:/Windows',
            hasSubfolder: true,
            isFolder: true,
            subitems: ['1/2/2021']
          },
          {
            label: 'Temp',
            path: 'C:/Temp',
            hasSubfolder: true,
            isFolder: true,
            lastModified: '1/1/2021'
          },
          {
            label: 'FolderWithoutSubfolder',
            path: 'C:/FolderWithoutSubfolder',
            hasSubfolder: false,
            isFolder: true,
            subitems: ['1/3/2021']
          },
          {
            label: 'File1',
            path: 'C:/File1.pdf',
            isFolder: false,
            ext: 'pdf',
            subitems: ['2/2/2021', '123,433']
          }
        ];
      }
      else if (data.path.endsWith("/FolderWithoutSubfolder")) {
        return [
          {
            label: 'file1.pdf',
            path: data.path + "/file1.pdf",
            ext: 'pdf',
            isFolder: false,
            subitems: ['2/2/2021', '123,234']
          },
          {
            label: 'file2.jpg',
            path: data.path + "/file2.jpg",
            ext: 'jpg',
            isFolder: false,
            subitems: ['2/2/2021', '123,234']
          }
        ];
      }
      else {
        return [
          {
            label: 'FolderWithSubfolder',
            path: data.path + "/FolderWithSubfolder",
            hasSubfolder: true,
            isFolder: true,
            subitems: ['1/1/2021']
          },
          {
            label: 'FolderWithoutSubfolder',
            path: data.path + "/FolderWithoutSubfolder",
            hasSubfolder: false,
            isFolder: true,
            subitems: ['1/1/2021']
          },
          {
            label: 'file1.pdf',
            path: data.path + "/file1.pdf",
            ext: 'pdf',
            isFolder: false,
            subitems: ['2/2/2021', '123,234']
          },
          {
            label: 'file2.jpg',
            path: data.path + "/file2.jpg",
            ext: 'jpg',
            isFolder: false,
            subitems: ['2/2/2021', '123,234']
          }
        ];
      }
    }
  </script>
</head>

<body>
  <div class="container">
   <div style='border: 1px solid silver; width: 100%; height: 300px;' id='fileexplorer1'></div>
  </div>
  <script>
    try {
      fetch(new Request("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js", { method: 'HEAD', mode: 'no-cors' })).then(function (response) {
        return true;
      }).catch(function (e) {
        var carbonScript = document.createElement("script");
        carbonScript.src = "//cdn.carbonads.com/carbon.js?serve=CK7DKKQU&placement=wwwjqueryscriptnet";
        carbonScript.id = "_carbonads_js";
        document.getElementById("carbon-block").appendChild(carbonScript);
      });
    } catch (error) {
      console.log(error);
    }
  </script>
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-header">
            <h4>Files</h4>
          </div>
          <div class="card-body" style="max-width: 200px; overflow-x: scroll;">
            
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="card">
          <div class="card-header">
            <h4>Editor</h4>
          </div>
          <div class="card-body">

            <div class="editor-container">
              <h2>HTML Code Editor</h2>



              <div class="theme-switcher">
                <label for="theme">Theme: </label>
                <select id="theme">
                  <option value="default">Default</option>
                  <option value="darcula">Darcula</option>
                  <option value="monokai">Monokai</option>
                  <option value="midnight">Midnight</option>
                  <option value="material-ocean">Material Ocean</option>
                  <option value="the-matrix">The Matrix</option>
                  <option value="ambiance">Ambiance</option>
                </select>
              </div>
              <textarea id="code" name="code">
<!DOCTYPE html>
<html>
  <head>
    <title>Sample HTML & PHP</title>
    <style>
      body { color: blue; }
    </style>
    <script>
      alert('Hello!');
    </script>
  </head>
  <body>
    <h1>Hello, CodeMirror!</h1>
    <p>Edit HTML, PHP, CSS, and JS. Switch themes.</p>
    <?php
      echo '<strong>This is PHP highlighted!</strong>';
      $foo = 42;
    ?>
  </body>
</html>
    </textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CodeMirror JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/clike/clike.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/php/php.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.js"></script>
  <!-- CodeMirror Addons for Search/Replace -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/searchcursor.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/search.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/dialog/dialog.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/jump-to-line.min.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/matchesonscrollbar.min.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/scroll/annotatescrollbar.min.js"></script>
  <script>
    // Initialize CodeMirror
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
      mode: "application/x-httpd-php",
      theme: "default",
      lineNumbers: true,
      autoCloseTags: true
    });
    // Theme switcher
    document.getElementById('theme').addEventListener('change', function () {
      editor.setOption('theme', this.value);
    });
  </script>
</body>

</html>