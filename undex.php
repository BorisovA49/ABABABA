<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ZonaOzona</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>

  <?php
$conn = new mysqli('localhost', 'root', '', 'Ozon');
if ($conn->connect_error) {
    die("Не удалось подключиться к серверу" . $conn->connect_error);
}
mysqli_set_charset($conn, 'utf8mb4_general_ci');
$sql = 'select * from cookie';
$result = mysqli_query($conn, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
$thingies = [];
foreach ($rows as $row) {
    array_push($thingies, ['id' => $row['id'], 'thing' => $row['name'], 'parid' => $row['parent'], 'children' => []]);
}

$lvl1 = [];
$lvl2 = [];
$lvl3 = [];
$mina = 0;
foreach ($thingies as $row) {
    if ($row ["parid"] == null) {
        array_push($lvl1, $row);
    }
}

foreach ($thingies as $row) {
    if (array_search($row, $lvl1) == false) {
        foreach ($lvl1 as $bebe) {
            if ($row["parid"] == $bebe["id"] && (array_search($row, $lvl2)) == false) {
                array_push($lvl2, $row);
            }
        }
    }
}
foreach ($thingies as $row) {
    if (array_search($row, $lvl1) == false) {
        foreach ($lvl2 as $bebe) {
            if ($row["parid"] == $bebe["id"] && (array_search($row, $lvl3)) == false) {
                array_push($lvl3, $row);
            }
        }
    }
}
?>
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal1222" type="button">Добавить</button>
    <div class="modal fade" id="exampleModal1222" tabindex="-1" aria-labelledby="exampleModalLabel1222" aria-hidden="true">
      <form method="post">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Добавить</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Добавить новую группу/товар</label>
                <input type="text" class="form-control" id="nme" name="tovar" required>
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Выберите категорию</label>
                <select class="form-select" id="cat" name="group" aria-label="parents" required>
                  <?php
        foreach ($lvl1 as $scrow) {
print('<option value="' . $scrow['id'] . '">' . $scrow['thing'] . '</option>');
foreach ($lvl2 as $sdcrow) {
    if ($sdcrow['parid'] == $scrow['id'] ) {
print('<option value="' . $sdcrow['id'] . '">' . ' &nbsp   &nbsp' . $sdcrow['thing'] . '</option>');
    }
}
}
        ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Закрыть</button>
              <button type="submit" class="btn btn-dark">Добавить</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <?php
  if (isset($_POST['tovar'])) {
          $dasql = "insert into cookie (name, parent) values ('" . $_POST['tovar'] . "', '" . $_POST['group'] . "')";
          if ($conn->query($dasql) === TRUE) {
              echo "Товар/группа добавлена";
              }
              else{
                  echo "Ошибка: " . $dasql . "<br>" . $conn->error;
                }
    }
    ?>
    <div class="list-group">

      <?php
    foreach ($lvl1 as $brow) {
        print ('
						<div class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle list-group-item list-group-item-action" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							' . $brow['thing'] . '
						  </button>
						  <ul class="dropdown-menu">');
        foreach ($lvl2 as $kubrow) {
            if ($kubrow["parid"] == $brow["id"]) {
                print('
			 <li><button class="dropdown-item btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal' . $kubrow['id'] . '" type="button">' . $kubrow['thing'] . '</button></li>');
            }
        }
        print ('</ul>
						</div>');
    }
    foreach ($lvl1 as $brow) {
        foreach ($lvl2 as $kubrow) {
            if ($kubrow["parid"] == $brow["id"]) {
                print('<div class="modal fade" id="exampleModal' . $kubrow['id'] . '" tabindex="-1" aria-labelledby="exampleModalLabel' . $kubrow['id'] . '" aria-hidden="true">
								  <div class="modal-dialog">
									<div class="modal-content">
									  <div class="modal-header">
										<h1 class="modal-title fs-5" id="exampleModalLabel' . $kubrow['id'] . '">' . $kubrow['thing'] . '</h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									  </div>
									  <div class="modal-body">
									  
									  ');
                foreach ($lvl3 as $nowhale) {
                    if ($nowhale["parid"] == $kubrow["id"]) {
                        print($nowhale ["thing"] . "      ");
                    }
                }
                print(' 
									  </div>
									  <div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary">Save changes</button>
									  </div>
									</div>
								  </div>
			</div>');
            }
        }
    }
    $conn->close();
    ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>
