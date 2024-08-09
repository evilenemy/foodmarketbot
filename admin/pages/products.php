<?php
require_once "../components/header.php";
require_once "../components/aside.php";
require_once "../configuration/functions.php";

if (isset($_GET['id'])) {
	deleteProduct($_GET['id']);
}
?>
    <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <h1 class="text-3xl text-black pb-6">Products</h1>
                <div class="w-full mt-6">
                    <div class="bg-white overflow-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Nomi</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kategoriya</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm"></th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-700">
														<?php
														$products = getAllProducts();
														for ($i = 0; $i < (count($products) % 2 === 0 ? count($products) : count($products) - 1); $i += 2) {
															?>
                                <tr>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $products[$i]['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><a
                                                href="edit_product.php?id=<?= $products[$i]['id'] ?>"><?= $products[$i]['name_uz']; ?></a>
                                    </td>
                                    <td class="text-left py-3 px-4"><?= $products[$i]['category']; ?></td>
                                    <td class="text-left py-3 px-4">
                                        <a href="products.php?id=<?= $products[$i]['id']; ?>"
                                           class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                            O'chirish
                                        </a>
                                    </td>
                                </tr>
                                <tr class="bg-gray-200">
                                    <td class="w-1/3 text-left py-3 px-4"><?= $products[$i + 1]['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><a
                                                href="edit_product.php?id=<?= $products[$i + 1]['id'] ?>"><?= $products[$i + 1]['name_uz']; ?></a>
                                    </td>
                                    <td class="text-left py-3 px-4"><?= $products[$i + 1]['category']; ?></td>
                                    <td class="text-left py-3 px-4">
                                        <a href="products.php?id=<?= $products[$i + 1]['id']; ?>"
                                           class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                            O'chirish
                                        </a>
                                    </td>
                                </tr>
														<?php }
														if (count($products) % 2 === 1) { ?>
                                <tr>
                                    <td class="w-1/3 text-left py-3 px-4"><?= end($products)['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><a
                                                href="edit_product.php?id=<?= end($products)['id'] ?>"><?= end($products)['name_uz']; ?></a>
                                    </td>
                                    <td class="text-left py-3 px-4"><?= end($products)['category']; ?></td>
                                    <td class="text-left py-3 px-4">
                                        <a href="products.php?id=<?= end($products)['id']; ?>"
                                           class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                            O'chirish
                                        </a>
                                    </td>
                                </tr>
														<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
<?php require_once "../components/footer.php"; ?>