/**
 * Obtiene los cines para llenar el selector.
 */
function ConsultarCinesParaSalaModel()
{
    $conn = OpenDB();
    $cines = [];

    try {
        $stmt = $conn->prepare(
            "CALL spGetCines()"
        );

        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $cines[] = $fila;
        }

        $resultado->free();
        $stmt->close();

        LimpiarResultadosSalaModel($conn);

        return $cines;
    } finally {
        CloseDB($conn);
    }
}