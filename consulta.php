<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "consulta";

    
    
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if (!$conn){
        die("No hay coneccion: ".mysqli_connect_error());
    }



    require('fpdf/fpdf.php');




    $usuario = $_POST["user"];

    $pass = $_POST["pass"];


    $query = mysqli_query($conn, "SELECT * FROM paciente WHERE nombrePaciente = '".$usuario."' and dniPaciente = '".$pass."'");
    $nr = mysqli_num_rows($query);

    if($nr == 1){


        $conId = "SELECT idPaciente FROM paciente WHERE nombrePaciente = '".$usuario."'";
        $resId = $conn->query($conId);
        $numCon = $resId->fetch_assoc();
        $finRes = $numCon['idPaciente'];



        $conHis = "SELECT fecha, descripcion FROM cita WHERE idPaciente = '".$finRes."'";
        $resHis = $conn->query($conHis);


        $conId = "SELECT idPaciente FROM paciente WHERE nombrePaciente = '".$usuario."'";
            $resId = $conn->query($conId);
            $numCon = $resId->fetch_assoc();
            $finRes = $numCon['idPaciente'];

        $conPa = "SELECT nombrePaciente, edad, sexoPaciente, telefonoPaciente, dniPaciente FROM paciente WHERE idPaciente = '".$finRes."'";
        $resPa = $conn->query($conPa);
        $finResPa = $resPa->fetch_assoc();



        class PDF extends FPDF
        {
        // Cabecera de página
        function Header()
        {
            
            $usuario = $_POST["user"];

            
            

           
            


            
            // Arial bold 15
            $this->SetFont('Arial','B',15);
            // Movernos a la derecha
            $this->Cell(80);
            // Título
            $this->Cell(30,10,'Historia Clinica',0,0,'C');
            $this->Ln(20);
            $this->SetFont('Arial','',15);
            
            
            
        }

        // Pie de página
        function Footer()
        {
            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Número de página
            $this->Cell(0,10,'Pagina '.$this->PageNo().'',0,0,'C');
        }
        }

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',11);

        $pdf->Cell(40, 10, 'Nombre de paciente:', 0, 0, '', 0);
        $pdf->Cell(40, 10, $finResPa['nombrePaciente'], 0, 1, '', 0);
        $pdf->Ln(-5);
        $pdf->Cell(40, 10, 'Edad:', 0, 0, '',0);
        $pdf->Cell(40, 10, $finResPa['edad'], 0, 1, '', 0);
        $pdf->Ln(-5);
        $pdf->Cell(40, 10, 'Sexo:', 0, 0, '', 0);
        $pdf->Cell(40, 10, $finResPa['sexoPaciente'], 0, 1, '', 0);
        $pdf->Ln(-5);
        $pdf->Cell(40, 10, 'Telefono:', 0, 0, '', 0);
        $pdf->Cell(40, 10, $finResPa['telefonoPaciente'], 0, 1, '', 0);


        $pdf->Ln(5);


        $pdf->Cell(40, 10, 'Fecha', 1, 0, 'C', 0);
        $pdf->Cell(140, 10, 'Descripcion', 1, 1, 'C', 0);
        while($row = $resHis->fetch_assoc()){

            $pdf->Cell(40, 10, $row['fecha'], 1, 0, 'C', 0);
            $pdf->Cell(140, 10, $row['descripcion'], 1, 1, 'C', 0);

        }  
        
        $pdf->Output();

    }
    else if ($nr == 0){
        header('location: consulta.html');
        
        
        
    }
?>