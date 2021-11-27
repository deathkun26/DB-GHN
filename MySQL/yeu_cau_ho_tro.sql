-- yêu cầu số 1
-- hiển thị danh sách các yêu cầu liên quan đến đơn hàng có mã vd là 33333331 theo thứ tự thời gian gửi.
SELECT maYC, loaiYC, noidung, trangthaiYC
FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
WHERE GUI_YEU_CAU.maVD = 33333331 AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC
ORDER BY GUI_YEU_CAU.thoigiangui DESC;
-- hiển thị danh sách các yêu cầu do một người dùng (có thể là nhân viên hoặc chủ) có mã người dùng là 12345678 theo thứ tự thời gian gửi.
SELECT maYC, loaiYC, noidung, trangthaiYC
FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
WHERE GUI_YEU_CAU.maND = 12345678 AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC
ORDER BY GUI_YEU_CAU.thoigiangui DESC;
-- hiển thị các đơn hàng có số yêu cầu hỗ trợ đã được xử lý nhiều hơn 2 lần.
SELECT maVD, COUNT(*)
FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
WHERE YEU_CAU_HO_TRO.trangthai = 2 AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC
GROUP BY maVD
HAVING COUNT(*) > 2
ORDER BY COUNT(*) ASC;
-- hiển thị danh sách người dùng có số yêu cầu hỗ trợ đang được xử lý nhiều hơn 2 lần.
SELECT maND, COUNT(*)
FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
WHERE YEU_CAU_HO_TRO.trangthai = 1 AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC
GROUP BY maND
HAVING COUNT(*) > 2
ORDER BY COUNT(*) ASC;

-- yêu cầu số 2
-- thủ tục hiển thị các yêu cầu đang được xử lý liên quan đến đơn hàng có mã vd là 3333331
DROP PROCEDURE IF EXISTS traCuuYCdangXuLy;
DELIMITER //

CREATE PROCEDURE traCuuYCdangXuLy(
		IN ma_van_don INT
)
BEGIN
	IF (ma_van_don < 10000000 OR ma_van_don > 99999999) THEN
		SELECT CONCAT('Mã vận đơn của bạn ', CAST(ma_van_don AS CHAR), 'nằm ngoài miền giá trị!');
	ELSE
		SELECT maYC, loaiYC, noidung, trangthaiYC
		FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
        WHERE GUI_YEU_CAU.maVD = ma_van_don AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC;
	END IF;
END //

DELIMITER ;

CALL traCuuYCdangXuLy(33333331);
-- thủ tục thêm vào bảng GUI_YEU_CAU
DELIMITER //

CREATE PROCEDURE them_yeu_cau(
		ma_nguoi_dung INT,
		ma_yeu_cau INT,
		ma_van_don INT
)
BEGIN
	IF (ma_van_don < 10000000 OR ma_van_don > 99999999) THEN
		SELECT CONCAT('Mã vận đơn của bạn ', CAST(ma_van_don AS CHAR), 'nằm ngoài miền giá trị!');
    ELSEIF (ma_nguoi_dung < 10000000 OR ma_nguoi_dung > 99999999) THEN
		SELECT CONCAT('Mã vận đơn của bạn ', CAST(ma_nguoi_dung AS CHAR), 'nằm ngoài miền giá trị!');
	ELSE
		INSERT INTO GUI_YEU_CAU
        VALUES (ma_nguoi_dung, ma_yeu_cau, ma_van_don);
	END IF;
    
END //

DELIMITER ;

CALL them_yeu_cau();

-- yêu cầu số 3
-- 
DELIMITER //

CREATE TRIGGER xoaYCHT
    BEFORE DELETE
    ON YEU_CAU_HO_TRO FOR EACH ROW
BEGIN
	DELETE FROM GUI_YEU_CAU WHERE GUI_YEU_CAU.maYC = OLD.maYC;
END//  

DELIMITER ;

