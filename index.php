<!DOCTYPE html>
<html lang="en">
<head>
        <title>ART Compiler</title>
        <script src="codemirror/lib/codemirror.js"></script>
        <link href="codemirror/lib/codemirror.css" rel="stylesheet"/>
        <script src="codemirror/mode/clike/test.js"></script>
        <script src="codemirror/mode/clike/clike1.js"></script>
        <script src="codemirror/addon/edit/closetag.js"></script>
        <script src="codemirror/addon/fold/brace-fold.js"></script>
        <script src="codemirror/addon/fold/comment-fold.js"></script>
        <script src="codemirror/addon/fold/foldcode.js"></script>
        <script src="codemirror/addon/fold/foldgutter.js"></script>
        <script src="codemirror/addon/fold/indent-fold.js"></script>
        <link href="codemirror/addon/fold/foldgutter.css" rel="stylesheet"/>
        <script src="codemirror/addon/selection/active-line.js"></script>
        <script src="codemirror/addon/edit/closebrackets.js"></script>
	<script src="codemirror/addon/edit/matchbrackets.js"></script>
        <script src="codemirror/addon/scroll/simplescrollbars.js"></script>
        <link href="codemirror/addon/scroll/simplescrollbars1.css" rel="stylesheet"/>

<style type="text/css">
.header{
        width:100%;
        height:40px;
	background:#f4f3f1;
        border: 1px solid #dbdfe5;
        text-align: center;
        font-size: 20px;
        padding-top:0px;
	color: green;
	font-weight:bold;
}
.container{
        width: 100%;
        min-height: 100%;
        padding-top:2px;
        display: flex;
}
.container .box1{
        background: #ffffff;
        border-radius: 3px;
        border: 2px solid #dbdfe5;
        flex: 1;

	overflow: auto;
}

.container .box1 #inside{
        width:100%;
        height:35px;
        border: 1px solid #dbdfe5;
        padding-bottom:10px;
        border-radius: 1px;
        background:#f4f3f1;
}
.container .box1.box2{
        background: #ffffff;
        border-radius: 3px;

	overflow: auto;
}
.container .box1.box2 #second{
        width:100%;
        height:35px;
        border: 1px solid #dbdfe5;
        border-radius: 5px;
        padding-bottom:10px;
        background:#f4f3f1;
}
.st{
	color:green;
 

}
.tab{
        float:right;
	margin:5px;
	display: inline-block;
        padding: 6px 12px;
         border-radius: 5px;


}
.tabb{
        float:left;
        margin-left:10px;
	margin:5px;
	display: inline-block;
        padding: 6px 12px;
         border-radius: 5px;


}
.btn {

	margin:5px;
	border: 1px solid #ccc;
	display: inline-block;
	padding: 6px 12px;
	font size: 30px;
	 border-radius: 5px;

}
 input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
    border-radius: 5px;
    margin:5px; 	
	
}
label {
  cursor: pointer;
}

</style>
</head>
<body>


       <div class="header">ART Compiler </div>
   <div class="container">
        <div class="box1">
		<form id="frm" action="#" method="post">
                <div id="inside" >
				<label for="input-file" class="custom-file-upload">Java File</label>
				 <input type="file" id="input-file" onchange=refresh()>
				

		   		<select class="tab" name="android" id="android">
                                 <option disabled selected value>Select Android Version</option>
                                 <option value="andO"<?php if($_POST['android']=='andO') echo 'selected="selected"'; ?>>Android 0</option>
                                 <option value="andP" <?php if($_POST['android']=='andP') echo 'selected="selected"'; ?>>Android P</option>
                                <option value="andQ"<?php if($_POST['android']=='andQ') echo 'selected="selected"'; ?>>Android Q</option>
                            </select>
                </div>	
                <textarea id="editor" name="field1"><?php if(isset($_POST['field1'])){echo htmlentities ($_POST['field1']);}?>
                </textarea>
<script>
document.getElementById('input-file')
  .addEventListener('change', getFile)

function getFile(event) {
        const input = event.target
  if ('files' in input && input.files.length > 0) {
          placeFileContent(
      document.getElementById('editor'),
      input.files[0])
  }
}

function placeFileContent(target, file) {
        readFileContent(file).then(content => {
        target.value = content
  }).catch(error => console.log(error))
}

function readFileContent(file) {
        const reader = new FileReader()
  return new Promise((resolve, reject) => {
    reader.onload = event => resolve(event.target.result)
    reader.onerror = error => reject(error)
    reader.readAsText(file)
  })
}
</script>

      <script>
                        var editor = CodeMirror.fromTextArea
                        (document.getElementById('editor'),{
                                mode:"text/x-java",
                                indentWithTabs: true,
                                smartIndent: true,
                                lineNumbers: true,
                                lineWrapping: true,
                                matchBrackets : true,
                                autofocus: true,
                                styleActiveLine: true,
                                lineNumbers:true,
                                autoCloseBrackets: true,
                                autoCloseTags:true,
				//theme:"dracula",
                                matchBrackets:true,
                                tabSize:5,
                                extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
                                foldGutter: true,
                                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
								});
                        editor.setSize(null, 600);
		function refresh()
		{
			setTimeout(function() {
			window.location.reload();
			},1000);        
		}

        </script>

                <?php
                  $output=shell_exec('bash dir.sh');
                  $name='text.txt';
                 $path = './classes/'.$name;
                 if (isset($_POST['field1']) ) {

                    $fh = fopen($path,"w+");
                    $string = $_POST['field1'];
                    fwrite($fh,$string);
                    fclose($fh);
                 }
                if(isset($_POST['android']))
                        {

                            $select1=$_POST['android'];
                            switch ($select1)
                            {
                                case 'andO':
                                       $output= shell_exec('bash run.sh andO');
                                        break;
                                case 'andP':
                                        $output=shell_exec('bash run.sh andP');
                                        break;
                                case 'andQ':
                                       $output= shell_exec('bash run.sh andQ');
                                        break;
                           }
                        }

                ?>
				</div>
        <div class="box1 box2">
                <div id="second" >

                           <select class="tabb" name="launguage" id="launguage" onchange='this.form.submit()' >
                                 <option disabled selected value>Convert To ..</option>
                                 <option value="bytecode" <?php if($_POST['launguage']=='bytecode') echo 'selected="selected"';   ?>>Java Bytecode</option>
                                 <option value="dexcode" <?php if($_POST['launguage']=='dexcode') echo 'selected="selected"';   ?>>Dalvik Bytecode</option>
                                <option value="machine" <?php if($_POST['launguage']=='machine') echo 'selected="selected"';   ?>>Assembly Code</option>
                            </select>
			     <button type ="button" class="btn"  onclick='this.form.submit()'>Refresh</button>
	
                          </form>
			   
                </div>
                <?php if (filesize('error.txt') == 0)
                        $flag=0;
                        else
                        $flag=1;
                ?>
                <?php
                  if($flag==1)
                  {
                        echo file_get_contents("error.txt");
                  }
                  else if (isset($_POST['launguage']))
                  {
                        $select2=$_POST['launguage'];
                        $Cfilename="NamingClass.txt";

                        $CContent  = file($Cfilename);
                        $NoOfCLine = count ( $CContent );

                ?>
				<?php switch ($select2){
                                        case 'machine':
                                                echo '<textarea id="asm">';
                                                for ( $j =0;$j<$NoOfCLine;$j++ )
                                                {
                                                        $Cfun_file="functionName$j.txt";
                                                        $Fline=file($Cfun_file);
                                                        $NoOfFlines=count($Fline);
                                                        echo $CContent[$j];
                                                        echo "{";
                                                        echo "\n";
                                                        for($k=0;$k<$NoOfFlines;$k++)
                                                        {
                                                                $f="machinefun$j$k.txt";
                                                                echo "\t".$Fline[$k]."\t{\n";
                                                                echo file_get_contents($f);
                                                                echo "\t}\n";
                                                        }
                                                        echo "}\n\n";
                                                }
                                                echo '</textarea>';
                                        break;
                                        case 'bytecode':
                                                echo '<textarea id="byte">';
                                                for ( $j =0;$j<$NoOfCLine;$j++ )
                                                {
                                                $Cfun_file="bytecodefun$j.txt";
                                                $Fline=file($Cfun_file);
                                                $NoOfFlines=count($Fline);
                                                echo $CContent[$j];
                                                echo "{";
                                                echo "\n";
                                                for($k=0;$k<$NoOfFlines;$k++)
                                                {
												$f="bytecode$j$k.txt";
                                                echo "\t".$Fline[$k]."\t{\n";
                                                echo file_get_contents($f);
                                                echo "\t}\n";
                                                }
                                                echo "}\n\n";
                                                }
                                                echo '</textarea>';
                                        break;
                                        case 'dexcode':
                                                echo '<textarea id="dex">';
                                                for ( $j =0;$j<$NoOfCLine;$j++ )
                                                {
                                                $Cfun_file="functionName$j.txt";
                                                $Fline=file($Cfun_file);
                                                $NoOfFlines=count($Fline);
                                                echo $CContent[$j];
                                                echo "{";
                                                echo "\n";
                                                for($k=0;$k<$NoOfFlines;$k++)
                                                {
                                                $f="dexcodefun$j$k.txt";
                                                echo "\t".$Fline[$k]."\t{\n";
                                                echo file_get_contents($f);
                                                echo "\t}\n";
                                                }
                                                echo "}\n\n";
                                                }
                                                echo '</textarea>';
                                        break;

                                }
                        }
						?>

           <script>
                var asm=CodeMirror.fromTextArea(document.getElementById('asm'),{
                mode:"text/x-asm",
                lineNumbers:true,
                styleActiveLine: true,
                readOnly:true,
                autoCloseBrackets: true,
                extraKeys: {"Ctrl-Q":function(cm){ cm.foldCode(cm.getCursor()); }},
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers","CodeMirror-foldgutter"]
                });
                asm.setSize(null,600);
            </script>
        <script>
                var bytec=CodeMirror.fromTextArea(document.getElementById('byte'),{
                mode:"text/x-javac",
                lineNumbers:true,
                styleActiveLine: true,
                readOnly:true,
                autoCloseBrackets: true,
                extraKeys: {"Ctrl-Q":function(cm){ cm.foldCode(cm.getCursor()); }},
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers","CodeMirror-foldgutter"]
                });
                bytec.setSize(null,600);
        </script>
		<script>
                var dex=CodeMirror.fromTextArea(document.getElementById('dex'),{
                mode:"text/x-dex",
                lineNumbers:true,
                styleActiveLine: true,
                readOnly:true,
                autoCloseBrackets: true,
                extraKeys: {"Ctrl-Q":function(cm){ cm.foldCode(cm.getCursor()); }},
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers","CodeMirror-foldgutter"]
                });
               dex.setSize(null,600);
          </script>

        </div>
    </div>


</body>
</html>




              
