-- yeu cau so 1
-- a.	2 Câu truy vấn từ 2 bảng trở lên có mệnh đề where, order by
-- b.	2 Câu truy vấn có aggreate function, group by, having, where và order by có liên kết từ 2 bảng trở lên

-- hiển thị danh sách các cửa hàng (mã, tên, trạng thái)
-- và số lượng nhân viên đang làm việc tại cửa hàng theo thứ tự số lượng từ bé đến lớn
SELECT maCH, tenCH, diachiCH, COUNT(*)
FROM LAM_VIEC_TAI AS LV, CUA_HANG AS CH
WHERE LV.maCH = CH.maCH
ORDER BY COUNT(*) ASC;



-- -- -- -- -- -- -- -- -- -- phần trên test chơi thôi nha -- -- -- -- -- -- -- -- -- 

-- hiển thị danh sách tất cả các cửa hàng (mã, tên, trạng thái CH) mà chủ cửa hàng tên "Thái"
SELECT CH.maCH, tenCH, trangthaiCH
FROM NGUOI_DUNG AS ND, CUA_HANG AS CH
WHERE ND.maND = CH.maCCH AND hotenND LIKE '%Thái%'
ORDER BY tenCH ASC;
-- hiển thị danh sách các cửa hàng (mã, tên, trạng thái CH) mà nhân viên có mã 23400001 đang làm việc.
SELECT CH.maCH, tenCH, diachiCH
FROM LAM_VIEC_TAI AS LV, CUA_HANG AS CH
WHERE LV.maCH = CH.maCH AND LV.maND = '23400001'
ORDER BY tenCH ASC;

-- hiển thị các cửa hàng mà có số lượng nhân viên nhiều hơn 1 người theo thứ tự từ lớn đến bé
SELECT CH.maCH, tenCH, COUNT(*) AS so_nhan_vien
FROM LAM_VIEC_TAI AS LV, CUA_HANG AS CH
WHERE LV.maCH = CH.maCH
GROUP BY LV.maCH, tenCH HAVING COUNT(*) > 1
ORDER BY COUNT(*) ASC;

-- hiển thị các cửa hàng mà có nhiều hơn 1 đơn hàng, xếp theo thứ tự số lượng đơn hàng từ bé đến lớn
SELECT CUA_HANG.maCH, tenCH, COUNT(*) AS so_don_hang
FROM CUA_HANG, DON_HANG
WHERE CUA_HANG.maCH = DON_HANG.maCH
GROUP BY CUA_HANG.maCH, tenCH HAVING COUNT(*) > 1
ORDER BY COUNT(*) ASC;

-- yêu cầu số 2
-- thủ tục hiển thị danh sách các cửa hàng của người dùng có mã là 12300000

DELIMITER //
CREATE PROCEDURE DanhSachCH (IN ma_chu_cua_hang INT)
BEGIN 
	IF (ma_chu_cua_hang < 10000000 OR ma_chu_cua_hang > 99999999) THEN
		SELECT CONCAT('Chủ cửa hàng', CAST(ma_chu_cua_hang AS CHAR), ' không tồn tại!');
	ELSE
		SELECT maCH, tenCH,trangthaiCH
		FROM CUA_HANG
		WHERE maCCH = ma_chu_cua_hang;
	END IF;
END //
DELIMITER ;

CALL DanhSachCH(12300000);

-- thủ tục thêm cửa hàng
DELIMITER //
CREATE PROCEDURE themCuaHang(
		IN ma_cua_hang INT,
		IN ma_chu INT,
		IN ten VARCHAR(30),
		IN dia_chi VARCHAR(100),
		IN so_dien_thoai CHAR(10)
)
BEGIN
	IF (ma_cua_hang < 1000000 OR ma_cua_hang > 9999999) THEN
		SELECT CONCAT('Mã cửa hàng của bạn ', CAST(ma_cua_hang AS CHAR), ' nằm ngoài miền giá trị!') AS 'Error';
	ELSEIF (ma_chu < 10000000 OR ma_chu > 99999999) THEN
		SELECT CONCAT('Mã chủ cửa hàng ', CAST(ma_chu AS CHAR), ' nằm ngoài miền giá trị!') AS 'Error';
	ELSEIF (EXISTS(SELECT * FROM CHU_CUA_HANG WHERE maND = ma_chu) = 0) THEN
		SELECT CONCAT('Mã chủ cửa hàng ', CAST(ma_chu AS CHAR), ' không tồn tại!') AS 'Error';
	ELSE
		INSERT INTO CUA_HANG (maCH, maCCH, tenCH, diachiCH, sodienthoaiCH)
        VALUES (ma_cua_hang, ma_chu, ten, dia_chi, so_dien_thoai);
	END IF;
END //
DELIMITER ;

DELETE FROM CUA_HANG WHERE maCH = 2340003;
CALL themCuaHang(2340003, 23400009, 'Cửa hàng 234 03', 'Thành phố Thủ Đức - Thành phố Hồ Chí Minh', '0775452222');

-- yêu cầu số 3
-- trigger cập nhật số lượng cửa hàng
DELIMITER //

CREATE TRIGGER capNhatSoLuongCH
    AFTER INSERT
    ON CUA_HANG FOR EACH ROW
BEGIN
	UPDATE CHU_CUA_HANG
    SET CHU_CUA_HANG.soluongcuahang = (CHU_CUA_HANG.soluongcuahang + 1)
    WHERE CHU_CUA_HANG.maND = NEW.maCCH;
END//  

DELIMITER ;
-- trigger khi xoá tài khoản người dùng có vai trò là chủ cửa hàng
DELIMITER //

CREATE TRIGGER xoaTaiKhoanChuCH
    BEFORE DELETE
    ON NGUOI_DUNG FOR EACH ROW
BEGIN
	DELETE FROM CHU_CUA_HANG WHERE CHU_CUA_HANG.maND = OLD.maND;
END//

DELIMITER ;

DELIMITER //

CREATE TRIGGER xoaChuCuaHang
    BEFORE DELETE
    ON CHU_CUA_HANG FOR EACH ROW
BEGIN
	DELETE FROM CUA_HANG WHERE CUA_HANG.maCCH = OLD.maND;
END//

DELIMITER ;

DELETE FROM NGUOI_DUNG WHERE maND = 34500000;

-- yêu cầu số 4
-- hàm tính số cửa hàng mà một nhân viên đang làm việc đồng thời
DROP FUNCTION IF EXISTS tinhSoCuaHang;
DELIMITER //

CREATE FUNCTION tinhSoCuaHang(
	ma_nhan_vien INT
) 
RETURNS INT
DETERMINISTIC
BEGIN
	DECLARE result INT DEFAULT 0;
	IF (ma_nhan_vien >= 10000000 AND ma_nhan_vien <= 99999999) THEN
		SET result = (SELECT COUNT(*) FROM LAM_VIEC_TAI WHERE LAM_VIEC_TAI.maND = ma_nhan_vien);
	END IF;
    RETURN result;
END//

DELIMITER ;

SELECT tinhSoCuahang(12300001);




