<?php
require_once "../components/header.php";
require_once "../components/aside.php";
require_once "../configuration/functions.php";

if (isset($_GET['id'])) {
	completeOrder($_GET['id']);
}
?>
    <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <h1 class="text-3xl text-black pb-6">Orders</h1>
                <div class="w-full mt-6">
                    <div class="bg-white overflow-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/6 text-left p-3 uppercase font-semibold text-sm">ID</th>
                                <th class="w-1/6 text-left p-3 uppercase font-semibold text-sm">ChatID</th>
                                <th class="w-1/6 text-left p-3 uppercase font-semibold text-sm">Narxi</th>
                                <th class="w-1/6 text-left p-3 uppercase font-semibold text-sm">Holati</th>
                                <th class="w-1/6 text-left p-3 uppercase font-semibold text-sm">Yaratilgan vaqt</th>
                                <th class="w-1/6 text-left p-3 uppercase font-semibold text-sm"></th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-700">
														<?php
														$orders = getAllOrders();
														for ($i = 0; $i < (count($orders) % 2 === 0 ? count($orders) : count($orders) - 1); $i += 2) {
															?>
                                <tr>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i]['id']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i]['chatID']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i]['price']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i]['status'] == 0 ? "aktiv" : "yetkazilgan"; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i]['created']; ?></td>
																	<?php if ($orders[$i]['status'] == 0) { ?>
                                      <td class="w-1/6 text-left p-3">
                                          <a href="orders.php?id=<?= $orders[$i]['id']; ?>"
                                             class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                              Tugatish
                                          </a>
                                      </td>
																	<?php }; ?>
                                </tr>
                                <tr class="bg-gray-200">
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i + 1]['id']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i + 1]['chatID']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i + 1]['price']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i + 1]['status'] == 0 ? "aktiv" : "yetkazilgan"; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= $orders[$i + 1]['created']; ?></td>

                                    <td class="w-1/6 text-left p-3">
																			<?php if ($orders[$i + 1]['status'] == 0) { ?>
                                          <a href="orders.php?id=<?= $orders[$i + 1]['id']; ?>"
                                             class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                              Tugatish
                                          </a>
																			<?php }; ?>
                                    </td>

                                </tr>
														<?php }
														if (count($orders) % 2 === 1) { ?>
                                <tr>
                                    <td class="w-1/6 text-left p-3"><?= end($orders)['id']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= end($orders)['chatID']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= end($orders)['price']; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= end($orders)['status'] == 0 ? "aktiv" : "yetkazilgan"; ?></td>
                                    <td class="w-1/6 text-left p-3"><?= end($orders)['created']; ?></td>
																	<?php if (end($orders)['status'] == 0) { ?>
                                      <td class="text-left py-3 px-4">
                                          <a href="orders.php?id=<?= $orders[$i]['id']; ?>"
                                             class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                              Tugatish
                                          </a>
                                      </td>
																	<?php }; ?>
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