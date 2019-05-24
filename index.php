<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
$connectionString = "DefaultEndpointsProtocol=https;AccountName=kingsub2;AccountKey=FrSGftUvGi8G9JubI/7jHaMWQVqNqaYIutNs4MPj7/d8bOFkrgwj9fmS3SU+2S2DGv9WTjp505G2OTdOfmaHxA==;EndpointSuffix=core.windows.net";
$blobClient = BlobRestProxy::createBlobService($connectionString);
$containerName = "kingsub2";
	
if (isset($_POST['submit'])) {
	$fileToUpload = $_FILES["fileToUpload"]["name"];
	$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
	echo fread($content, filesize($fileToUpload));
		
	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	header("Location: index.php");
}	
	
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Storage Blob Azure</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
		<link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">
		<!-- Bootstrap core CSS -->
		<link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="starter-template.css" rel="stylesheet">
	</head>
	
	<body>

		<div class="panel panel-default">
			<div class="panel-heading">
				<b>Masukan Gambar :</b>
			</div>
			<div class="panel-body">
				<form action="index.php" method="post" enctype="multipart/form-data">
					<input type="file" class="btn btn-default" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
					<input type="submit" class="btn btn-primary" name="submit" value="Upload">
				</form>	
			</div>
		</div>
		<br>
		<table class="table table-bordered table-stripe table-hover">
			<thead>
				<tr>
					<th>URL</th>
					<th>Action</th>
				</tr>
			</thead>
		
				<tbody>
						<?php
						do {
							foreach ($result->getBlobs() as $blob) {
						?>						
						<tr>
							<td><?php echo $blob->getUrl() ?></td>
							<td>
								<form action="lihat.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">						
									<input type="submit" class="btn btn-primary" name="submit"  value="Lihat">
								</form>
							</td>
						</tr>
						<?php
							} $listBlobsOptions->setContinuationToken($result->getContinuationToken());
						} while($result->getContinuationToken());
						?>
				</tbody>	
 		</table>
			
			<!-- Placed at the end of the document so the pages load faster -->
			<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
			<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
			<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
			<script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
			
	</body>
</html>