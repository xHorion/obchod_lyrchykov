<!DOCTYPE html>
<html lang="en">

<body>

<!-- ***** Preloader Start ***** -->
<div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>


<!-- ***** Preloader End ***** -->

<!-- ***** Header Area Start ***** -->
<?php include_once "parts/header.php" ?>
<!-- ***** Header Area End ***** -->

<div class="page-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Často kladené otázky a odpovede</h3>
                <span class="breadcrumb"><a href="index.php">Domov</a>  >  QnA</span>
            </div>
        </div>
    </div>
</div>

<div class="accordion accordion-flush" id="accordionFlushExample">
    <?php
    require_once "classes/qna.php";

    use qna_class\QnA;

    $qna = new QnA();
    $data = $qna->getAll();

    if (!empty($data)) {
        $index = 0;
        foreach ($data as $item) {
            $index++;
            $question = htmlspecialchars($item['Otazka']);
            $answer = htmlspecialchars($item['Odpoved']);
            ?>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-heading<?php echo $index; ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $index; ?>">
                        <?php echo $question; ?>
                    </button>
                </h2>
                <div id="flush-collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $index; ?>" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <?php echo $answer; ?>
                    </div>
                </div>
            </div>

            <?php
        }
    } else {
        echo "<p>Žiadne otázky a odpovede zatiaľ nie sú dostupné.</p>";
    }
    ?>
</div>

<?php include_once "parts/footer.php" ?>

</body>
</html>