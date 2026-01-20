<?p
public function createCardData($patient_id) {
    $query = "SELECT * FROM patients WHERE patient_id = :patient_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
