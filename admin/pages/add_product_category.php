<?php
require_once "../components/header.php";
require_once "../components/aside.php";
require_once "../configuration/functions.php";

if (isset($_POST['name_uz'])) {
	createProduct($_POST['name_uz'], $_POST['name_ru'], $_POST['info_uz'], $_POST['info_ru'], $_POST['categoryId'], $_COOKIE['options'], uploadImage($_FILES['photo']));
	deleteAllCookies();
	header("Location: /foodmarketbot/admin/pages/products.php");
}

if (isset($_POST['cat_name_uz'])) {
	deleteAllCookies();
	createCategory($_POST['cat_name_uz'], $_POST['cat_name_ru']);
	header("Location: /foodmarketbot/admin/pages/categories.php");
}

$productNameUz = empty($_COOKIE['product_name_uz']) ? "" : $_COOKIE['product_name_uz'];
$productNameRu = empty($_COOKIE['product_name_ru']) ? "" : $_COOKIE['product_name_ru'];
$productDescriptionUz = empty($_COOKIE['product_description_uz']) ? "" : $_COOKIE['product_description_uz'];
$productDescriptionRu = empty($_COOKIE['product_description_ru']) ? "" : $_COOKIE['product_description_ru'];
$productCategoryId = empty($_COOKIE['product_category_id']) ? "" : $_COOKIE['product_category_id'];
$options = json_decode($_COOKIE['options'], true);
?>
    <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <h1 class="w-full text-3xl text-black pb-6">Mahsulot qo'shish</h1>
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 my-6 pr-0 lg:pr-2">
                        <p class="text-xl pb-6 flex items-center">
                            <i class="fas fa-list mr-3"></i> Mahsulot qo'shish
                        </p>
                        <div class="leading-loose">
                            <form id="product_form" method="POST" enctype="multipart/form-data"
                                  class="p-10 bg-white rounded shadow-xl">
                                <div class="">
                                    <label class="block text-sm text-gray-600" for="product_name_uz">Mahsulot nomi
                                        (o'zbek tilida)</label>
                                    <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded"
                                           id="product_name_uz"
                                           name="name_uz" type="text" required placeholder="Product Name"
                                           aria-label="Name" value="<?= $productNameUz ?>">
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600" for="product_name_ru">Mahsulot nomi (rus
                                        tilida)</label>
                                    <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded"
                                           id="product_name_ru"
                                           name="name_ru" type="text" required placeholder="Product Name"
                                           aria-label="Name" value="<?= $productNameRu ?>">
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600" for="product_description_uz">Mahsulot
                                        tavfsifi (o'zbek
                                        tilida)</label>
                                    <input class="w-full px-5  py-4 text-gray-700 bg-gray-200 rounded"
                                           id="product_description_uz"
                                           name="info_uz" type="text" required="" placeholder="Product Description"
                                           value="<?= $productDescriptionUz ?>">
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600" for="product_description_ru">Mahsulot
                                        tavfsifi (rus
                                        tilida)</label>
                                    <input class="w-full px-5  py-4 text-gray-700 bg-gray-200 rounded"
                                           id="product_description_ru"
                                           name="info_ru" type="text" required="" placeholder="Product Description"
                                           value="<?= $productDescriptionRu ?>">
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600" for="product_category_id">Mahsulot
                                        kategoriyasi</label>
                                    <select required name="categoryId"
                                            id="product_category_id"
                                            class="w-full px-5  py-4 text-gray-700 bg-gray-200 rounded cursor-pointer">
                                        <option value="" <?= $productCategoryId ? "" : "selected" ?> disabled> --
                                            Kategoriya tanlang --
                                        </option>
																			<?php
																			$categories = getAllCategories();
																			foreach ($categories as $category) {
																				?>
                                          <option class="cursor-pointer" <?= $productCategoryId === $category['id'] ? "selected" : "" ?>
                                                  value="<?= $category['id'] ?>"><?= $category['uz'] ?></option>
																			<?php } ?>
                                    </select>
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600" for="photoBtn">Mahsulot rasmi</label>
                                    <button type="button"
                                            id="photoBtn"
                                            onclick="document.getElementById('photo').click()"
                                            class="w-full py-2 px-4 rounded bg-blue-700 text-white hover:bg-blue-800 active:scale-[0.8]">
                                        Rasm yuklash
                                    </button>
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
                                    </script>
                                    <input name="photo" class="hidden" id="photo" onchange="getFileName()" type="file"/>
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600" for="option">Mahsulot turlari</label>
                                    <script>
                                        const addToOptions = () => {
                                            const text = String(document.getElementById('option').value).split(":");
                                            const options = JSON.parse(localStorage.getItem('options')) || [];
                                            options.push({name: text[0], price: text[1]});
                                            localStorage.setItem('options', JSON.stringify(options));
                                            document.cookie = "options=" + JSON.stringify(options);
                                            document.cookie = "product_name_uz=" + document.getElementById('product_name_uz').value;
                                            document.cookie = "product_name_ru=" + document.getElementById('product_name_ru').value;
                                            document.cookie = "product_description_uz=" + document.getElementById('product_description_uz').value;
                                            document.cookie = "product_description_ru=" + document.getElementById('product_description_ru').value;
                                            document.cookie = "product_category_id=" + document.getElementById('product_category_id').value;
                                            location.reload();
                                        }
                                    </script>
                                    <div class="w-full flex items-center justify-between">
                                        <input type="text" class="px-5 py-2 text-gray-700 bg-gray-200 rounded"
                                               id="option"/>
                                        <input type="submit" name="submit_product" class="hidden"/>
                                        <a href="javascript:addToOptions();"
                                           class="py-2 px-4 rounded bg-blue-500 hover:bg-blue-600 text-white">Tur
                                            qo'shish
                                        </a>
                                    </div>
                                    <div class="w-full mt-3">
																			<?php
																			if (!empty($options)) {
																				for ($i = 0; $i < count($options); $i++) {
																					?>
                                            <div class="mt-2 bg-gray-100 px-4 py-2 flex items-center justify-between">
                                                <p><?= ($i + 1) . ". " . $options[$i]['name']; ?></p>
                                                <p><?= $options[$i]['price'] ?> so'm</p>
                                            </div>
																				<?php }
																			} ?>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button
                                            class="px-4 py-2 text-white font-light tracking-wider bg-gray-900 rounded"
                                    >Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 mt-6 pl-0 lg:pl-2">
                        <p class="text-xl pb-6 flex items-center">
                            <i class="fas fa-list mr-3"></i> Kategoriya qo'shish
                        </p>
                        <div class="leading-loose">
                            <form method="POST" class="p-10 bg-white rounded shadow-xl">
                                <div class="">
                                    <label class="block text-sm text-gray-600" for="category_name_uz">Kategoriya nomi
                                        (o'zbek tilida)</label>
                                    <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded"
                                           id="category_name_uz"
                                           name="cat_name_uz" type="text" required placeholder="Kategoriya nomi"
                                           aria-label="Name">
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600" for="category_name_ru">Kategoriya nomi
                                        (rus tilida)</label>
                                    <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded"
                                           id="category_name_ru"
                                           name="cat_name_ru" type="text" required placeholder="Kategoriya nomi"
                                           aria-label="Name">
                                </div>
                                <div class="mt-6">
                                    <button class="px-4 py-1 text-white font-light tracking-wider bg-gray-900 rounded"
                                            type="submit">Qo'shish
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

<?php require_once "../components/footer.php" ?>