-- yêu cầu số 1
-- hiển thị danh sách các yêu cầu liên quan đến đơn hàng có mã vd là 12222221 theo thứ tự thời gian gửi.
SELECT YEU_CAU_HO_TRO.maYC, loaiYC, noidungYC, trangthaiYC
FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
WHERE GUI_YEU_CAU.maVD = 12222221 AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC
ORDER BY GUI_YEU_CAU.thoigiangui DESC;
-- hiển thị danh sách các yêu cầu do một người dùng (có thể là nhân viên hoặc chủ) có mã người dùng là 12300001 theo thứ tự thời gian gửi.
SELECT YEU_CAU_HO_TRO.maYC, loaiYC, noidungYC, trangthaiYC
FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
WHERE GUI_YEU_CAU.maND = 12300001 AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC
ORDER BY GUI_YEU_CAU.thoigiangui DESC;
-- hiển thị các đơn hàng có ít nhất một đơn hàng đã được xử lý theo thứ tự giảm dần số lượng yêu cầu.
SELECT maVD, COUNT(*) AS soyeucau
FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
WHERE YEU_CAU_HO_TRO.trangthaiYC = 2 AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC
GROUP BY maVD
HAVING COUNT(*) > 0
ORDER BY COUNT(*) DESC;
-- hiển thị danh sách người dùng có ít nhất một đơn hàng đang được xử lý theo thứ tự giảm dần số lượng yêu cầu.
SELECT maND, COUNT(*) AS soyeucau
FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
WHERE YEU_CAU_HO_TRO.trangthaiYC = 1 AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC
GROUP BY maND
HAVING COUNT(*) > 0
ORDER BY COUNT(*) DESC;

-- yêu cầu số 2
-- thủ tục hiển thị các yêu cầu liên quan đến một đơn hàng
DROP PROCEDURE IF EXISTS traCuuYeuCau;
DELIMITER //

CREATE PROCEDURE traCuuYeuCau(
		IN ma_van_don INT
)
BEGIN
	IF (ma_van_don < 10000000 OR ma_van_don > 99999999) THEN
		SELECT CONCAT('Mã vận đơn của bạn ', CAST(ma_van_don AS CHAR), 'nằm ngoài miền giá trị!') AS 'Error';
	ELSEIF (EXISTS(SELECT * FROM DON_HANG WHERE maVD = ma_van_don) = 0) THEN
		SELECT CONCAT('Mã vận đơn ', CAST(ma_van_don AS CHAR), ' không tồn tại!') AS 'Error';
	ELSE
		SELECT YEU_CAU_HO_TRO.maYC, loaiYC, noidungYC, trangthaiYC
		FROM YEU_CAU_HO_TRO, GUI_YEU_CAU
        WHERE GUI_YEU_CAU.maVD = ma_van_don AND YEU_CAU_HO_TRO.maYC = GUI_YEU_CAU.maYC;
	END IF;
END //

DELIMITER ;

CALL traCuuYeuCau(12222221);
-- thủ tục thêm vào bảng GUI_YEU_CAU
DROP PROCEDURE IF EXISTS themYeuCau;
DELIMITER //

CREATE PROCEDURE themYeuCau(
		ma_nguoi_dung INT,
		ma_yeu_cau INT,
		ma_van_don INT
)
BEGIN
	IF (ma_van_don < 10000000 OR ma_van_don > 99999999) THEN
		SELECT CONCAT('Mã vận đơn của bạn ', CAST(ma_van_don AS CHAR), 'nằm ngoài miền giá trị!');
    ELSEIF (ma_nguoi_dung < 10000000 OR ma_nguoi_dung > 99999999) THEN
		SELECT CONCAT('Mã người dùng của bạn ', CAST(ma_nguoi_dung AS CHAR), 'nằm ngoài miền giá trị!');
	ELSEIF (EXISTS(SELECT * FROM DON_HANG WHERE maVD = ma_van_don) = 0) THEN
		SELECT CONCAT('Mã vận đơn của bạn ', CAST(ma_van_don AS CHAR), ' không tồn tại!') AS 'Error';
	ELSEIF (EXISTS(SELECT * FROM NGUOI_DUNG WHERE maND = ma_nguoi_dung) = 0) THEN
		SELECT CONCAT('Mã người dùng của bạn ', CAST(ma_nguoi_dung AS CHAR), ' không tồn tại!') AS 'Error';
	ELSE
		INSERT INTO GUI_YEU_CAU(maND, maYC, maVD)
        VALUES (ma_nguoi_dung, ma_yeu_cau, ma_van_don);
	END IF;
    
END //

DELIMITER ;

INSERT INTO YEU_CAU_HO_TRO(maYC, loaiYC, noidungYC)
VALUES (99000007, 1, 'Test fck tired!');
CALL themYeuCau(23400001, 99000007, 21111111);

-- yêu cầu số 3
-- trigger xoá yêu cầu hỗ trợ
DELIMITER //

CREATE TRIGGER xoaYCHT
    BEFORE DELETE
    ON GUI_YEU_CAU FOR EACH ROW
BEGIN
	DELETE FROM YEU_CAU_HO_TRO WHERE YEU_CAU_HO_TRO.maYC = OLD.maYC;
END//  

DELIMITER ;

DELETE FROM GUI_YEU_CAU WHERE maYC = 99000007;
-- trigger xoá yêu cầu hỗ trợ khi xoá đơn hàng
DELIMITER //

CREATE TRIGGER xoaYCHT
    BEFORE DELETE
    ON DON_HANG FOR EACH ROW
BEGIN
	DELETE FROM GUI_YEU_CAU WHERE GUI_YEU_CAU.maVD = OLD.maVD;
END//  

DELIMITER ;

