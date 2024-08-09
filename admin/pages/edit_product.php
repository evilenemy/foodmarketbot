<?php
require_once "../components/header.php";
require_once "../components/aside.php";
require_once "../configuration/functions.php";

if (isset($_POST['id'])) {
	$photo = !empty($_FILES['photo']['tmp_name']) ? $_FILES['photo'] : false;
	$options = !empty($_COOKIE['options']) ? $_COOKIE['options'] : getProductInfo($_POST['id'])['options'];
	editProduct($_POST['id'], $_POST['name_uz'], $_POST['name_ru'], $_POST['info_uz'], $_POST['info_ru'], $_POST['categoryId'], $options, $photo);
	deleteAllCookies();
	header("Location: /foodmarketbot/admin/pages/products.php");
}

if (!empty($_COOKIE['delete_image'])) {
	deleteImage($_COOKIE['delete_image']);
	deleteAllCookies();
	header("Location: /foodmarketbot/admin/pages/products.php");
}

$product = getProductInfo($_GET['id']);
$options = !empty($_COOKIE['options']) ? $_COOKIE['options'] : $product['options'];
$rootPath = "https://u13602.xvest1.ru/foodmarketbot/";
?>

<div class="relative w-full flex flex-col h-screen overflow-y-hidden">
    <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
        <main class="w-full flex-grow p-6">
            <h1 class="w-full text-3xl text-black pb-6">Mahsulot o'zgartirish</h1>
            <div class="flex flex-wrap">
                <div class="w-full lg:w-1/2 my-6 pr-0 lg:pr-2">
                    <p class="text-xl pb-6 flex items-center">
                        <i class="fas fa-list mr-3"></i> Mahsulot o'zgartirish
                    </p>
                    <div class="leading-loose">
                        <form method="post" action="edit_product.php" enctype="multipart/form-data"
                              class="p-10 bg-white rounded shadow-xl">
                            <div class="">
                                <label class="block text-sm text-gray-600" for="product_name_uz">Mahsulot nomi
                                    (o'zbek tilida)</label>
                                <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded"
                                       id="product_name_uz"
                                       name="name_uz" type="text" required placeholder="Product Name"
                                       aria-label="Name" value="<?= $product['name_uz'] ?>">
                                <input type="text" name="id" value="<?= $product['id']; ?>" class="hidden"/>
                            </div>
                            <div class="mt-2">
                                <label class="block text-sm text-gray-600" for="product_name_ru">Mahsulot nomi (rus
                                    tilida)</label>
                                <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded"
                                       id="product_name_ru"
                                       name="name_ru" type="text" required placeholder="Product Name"
                                       aria-label="Name" value="<?= $product['name_ru'] ?>">
                            </div>
                            <div class="mt-2">
                                <label class="block text-sm text-gray-600" for="product_description_uz">Mahsulot
                                    tavfsifi (o'zbek
                                    tilida)</label>
                                <input class="w-full px-5  py-4 text-gray-700 bg-gray-200 rounded"
                                       id="product_description_uz"
                                       name="info_uz" type="text" required="" placeholder="Product Description"
                                       value="<?= $product['info_uz'] ?>">
                            </div>
                            <div class="mt-2">
                                <label class="block text-sm text-gray-600" for="product_description_ru">Mahsulot
                                    tavfsifi (rus
                                    tilida)</label>
                                <input class="w-full px-5  py-4 text-gray-700 bg-gray-200 rounded"
                                       id="product_description_ru"
                                       name="info_ru" type="text" required="" placeholder="Product Description"
                                       value="<?= $product['info_ru'] ?>">
                            </div>
                            <div class="mt-2">
                                <label class="block text-sm text-gray-600" for="product_category_id">Mahsulot
                                    kategoriyasi</label>
                                <select required name="categoryId"
                                        id="product_category_id"
                                        class="w-full px-5  py-4 text-gray-700 bg-gray-200 rounded cursor-pointer">
																	<?php
																	$categories = getAllCategories();
																	foreach ($categories as $category) {
																		?>
                                      <option class="cursor-pointer" <?= $product['categoryId'] === $category['id'] ? "selected" : "" ?>
                                              value="<?= $category['id'] ?>"><?= $category['uz'] ?></option>
																	<?php } ?>
                                </select>
                            </div>
                            <div class="mt-2">
                                <img src="<?= $rootPath . $product['photoUrl'] ?>" alt="pic"/>
                                <label class="block text-sm text-gray-600" for="photoBtn">Mahsulot rasmi</label>
                                <button type="button"
                                        id="photoBtn"
                                        onclick="document.getElementById('photo').click()"
                                        class="w-full py-2 px-4 rounded bg-blue-700 text-white hover:bg-blue-800">
                                    Rasmni qayta yuklash
                                </button>
                                <a href="javascript:deleteImage(<?= $product['id']; ?>)"
                                   class="block mt-2 text-center w-full py-2 px-4 rounded bg-red-600 text-white hover:bg-red-700">Joriy
                                    rasmni
                                    o'chirish</a>
                                <p class="hidden" id="file_input">Rasm nomi: <span id="selected_file_name"></span>
                                </p>
                                <script>
                                    const getFileName = () => {
                                        const fileInput = document.getElementById('photo');
                                        if (fileInput.files[0]) {
                                            document.getElementById('file_input').classList.remove('hidden');
                                            document.getElementById('selected_file_name').innerText = fileInput.files[0].name;
                                        }
                                    }
                                    const deleteImage = (id) => {
                                        document.cookie = "delete_image=" + id;
                                        location.reload();
                                    }
                                </script>
                                <input name="photo" class="hidden" id="photo" onchange="getFileName()" type="file"/>
                            </div>
                            <div class="mt-2">
                                <label class="block text-sm text-gray-600" for="option">Mahsulot turlari</label>
                                <script>
                                    const getCookie = (cookieName) => {
                                        const name = cookieName + "=";
                                        const decodedCookie = decodeURIComponent(document.cookie);
                                        const cookieArray = decodedCookie.split(';');
                                        for (let i = 0; i < cookieArray.length; i++) {
                                            let cookie = cookieArray[i].trim();
                                            if (cookie.indexOf(name) === 0) {
                                                return cookie.substring(name.length, cookie.length);
                                            }
                                        }
                                        return "";
                                    }
                                    const addToOptions = () => {
                                        const text = String(document.getElementById('option').value).split(":");
                                        const options = JSON.parse(<?= json_encode($options) ?>);
                                        options.push({name: text[0], price: Number(text[1])});
                                        document.cookie = "options=" + JSON.stringify(options);
                                        location.reload();
                                    }
                                    const deleteOption = (index) => {
                                        let options = JSON.parse(<?= json_encode($options) ?>);
                                        options = options.filter((option) => option.name !== options[index].name && option.price !== options[index].price);
                                        localStorage.setItem('options', JSON.stringify(options));
                                        document.cookie = "options=" + JSON.stringify(options);
                                        location.reload();
                                    }
                                </script>
                                <div class="w-full flex items-center justify-between">
                                    <input type="text" class="px-5 py-2 text-gray-700 bg-gray-200 rounded"
                                           id="option"/>
                                    <a href="javascript:addToOptions();"
                                       class="py-2 px-4 rounded bg-blue-500 hover:bg-blue-600 text-white">Tur
                                        qo'shish
                                    </a>
                                </div>
                                <div class="w-full mt-3">
																	<?php
																	$options = json_decode($options, true);
																	if (!empty($options)) {
																		for ($i = 0; $i < count($options); $i++) {
																			?>
                                        <div class="mt-2 bg-gray-100 px-4 py-2 flex items-center justify-between">
                                            <p><?= ($i + 1) . ". " . $options[$i]['name']; ?></p>
                                            <p><?= $options[$i]['price'] ?> so'm</p>
                                            <button type="button"
                                                    class="bg-red-600 text-white hover:bg-red-700 rounded py-2 px-4"
                                                    onclick="deleteOption(<?= $i ?>)">O'chirish
                                            </button>
                                        </div>
																		<?php }
																	} ?>
                                </div>
                            </div>
                            <div class="mt-6">
                                <button
                                        class="px-4 py-2 text-white font-light tracking-wider bg-gray-900 rounded"
                                >O'zgartirish
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once "../components/footer.php"; ?>
