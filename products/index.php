<?php
  include '../app/config.php'; 
	include "../app/ProductsController.php";
	include "../app/BrandsController.php";

	$bransController = new BrandsController();
	$brands = $bransController->getBrands();

	$productController = new ProductsController();
	$products = $productController->getProducts();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include '../layouts/head.template.php'; ?>
	</head>
	<body>

		<!-- NAVBAR -->
		<?php include '../layouts/nav.template.php'; ?>
		<!-- NAVBAR -->

		<div class="container-fluid">
			
			<div class="row">
				
				<!-- SIDEBAR -->
				<?php include '../layouts/sidebar.template.php'; ?>
				<!-- SIDEBAR -->

				<div class="col-md-10 col-lg-10 col-sm-12">

					<section> 
						<div class="row bg-light m-2">
							<div class="col">
								<label>
									/Productos
								</label>
							</div>
							<div class="col">
								<button data-bs-toggle="modal" data-bs-target="#addProductModal" class=" float-end btn btn-primary">
									Añadir producto
								</button>
							</div>
						</div> 
					</section>
					
					<section>
						
						<div class="row">
							
							<?php if (isset($products) && count($products)): ?>
							<?php foreach ($products as $product): ?>

							<div class="col-md-4 col-sm-12"> 

								<div class="card mb-2">
								  <img src="<?= $product->cover ?>" class="card-img-top" alt="...">
								  <div class="card-body">
								    
								    <h5 class="card-title">
								    	<?= $product->name ?>
								    </h5>

								    <h6 class="card-subtitle mb-2 text-muted">
								    	<?= $product->brand->name ?>
								    </h6>
								    <p class="card-text">
								    	<?= $product->description ?>
								    </p>

								    <div class="row">
									    <a data-product='<?= json_encode($product) ?>' onclick="editProduct(this)" data-bs-toggle="modal" data-bs-target="#addProductModal" href="#" class="btn btn-warning mb-1 col-6">
									    	Editar
									    </a>
									    <a  onclick="eliminar(<?= $product->id ?>)" href="#" class="btn btn-danger mb-1 col-6">
									    	Eliminar
									    </a> 
									    <a href="details.php?slug=<?= $product->slug ?>" class="btn btn-info col-12">
									    	Detalles
									    </a>
								    </div>

								  </div>
								</div>  

							</div>

							<?php endforeach ?>
								
							<?php endif ?>

						</div>

					</section> 

					 
				</div>

			</div>

		</div>

		<!-- Modal -->
		<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>

		      <form enctype="multipart/form-data" method="post" action="<?=BASE_PATH?>prod">

			      <div class="modal-body">
			        
			        
			        <div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1">@</span>
					  <input id="name" name="name" required type="text" class="form-control" placeholder="Nombre" aria-label="Username" aria-describedby="basic-addon1">
					</div>

					<div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1">@</span>
					  <input id="slug" name="slug" required type="text" class="form-control" placeholder="Url amigable" aria-label="Username" aria-describedby="basic-addon1">
					</div>

					<div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1">@</span>
					  <textarea id="description" name="description" placeholder="Escríbe aquí" class="form-control"></textarea> 
					</div>

					<div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1">@</span>
					  <input id="features" name="features" required type="text" class="form-control" placeholder="Carácteristicas" aria-label="Username" aria-describedby="basic-addon1">
					</div>

					<div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1">@</span>

					  <select id="brand_id" name="brand_id" required class="form-control">
					  	<?php foreach ($brands as $brand): ?>
					  	<option value="<?= $brand->id ?>" >
					  		<?= $brand->name ?>
					  	</option>
					  	<?php endforeach ?>
					  	
					  </select> 
					</div>

					<div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1">@</span>
					  <input name="cover" required type="file" class="form-control" placeholder="Nombre" aria-label="Username" aria-describedby="basic-addon1">
					</div>

			      </div>

			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
			        	Close
			        </button>
			        <button type="submit" class="btn btn-primary">
			        	Save changes
			        </button>
			      </div>

			      <input type="hidden" id="action" name="action" value="create">
			      <input type="hidden" id="id_product" name="id">
            <input type="hidden" name="super_token" value="<?= $_SESSION['super_token'] ?>">

		      </form>

		    </div>
		  </div>
		</div>

		<?php include '../layouts/scripts.template.php'; ?>
		
		<script type="text/javascript">
			function eliminar(id)
			{
				swal({
				  title: "Are you sure?",
				  text: "Once deleted, you will not be able to recover this imaginary file!",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {

				  	var bodyFormData = new FormData();

				  	bodyFormData.append('id', id);
				  	bodyFormData.append('action', 'delete');
            bodyFormData.append('super_token',"<?= $_SESSION["super_token"]?>");

				  	axios.post('<?=BASE_PATH?>prod', bodyFormData)
					  .then(function (response) {
					    if (response.data) {
					    	swal("Poof! Your imaginary file has been deleted!", {
						      icon: "success",
						    });
					    }else{
					    	swal("Error", {
						      icon: "error",
						    });
					    }
					  })
					  .catch(function (error) {
					    console.log(error);
					  });

				    
				  } else {
				    swal("Your imaginary file is safe!");
				  }
				});
			}

			function editProduct(target)
			{
			
				let product = JSON.parse( target.dataset.product )

				document.getElementById('name').value = product.name
				document.getElementById('slug').value = product.slug
				document.getElementById('description').value = product.description
				document.getElementById('features').value = product.features
				document.getElementById('brand_id').value = product.brand_id
				document.getElementById('id_product').value = product.id
				document.getElementById('action').value = 'update'
        
			}
		</script>
	</body>
</html>











