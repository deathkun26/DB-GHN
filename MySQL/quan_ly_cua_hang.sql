-- yeu cau so 1
-- a.	2 Câu truy vấn từ 2 bảng trở lên có mệnh đề where, order by
-- b.	2 Câu truy vấn có aggreate function, group by, having, where và order by có liên kết từ 2 bảng trở lên


-- hiển thị danh sách tất cả các cửa hàng (mã, tên, trạng thái CH) mà chủ cửa hàng tên "Thái"
SELECT CH.maCH, tenCH, trangthaiCH, ND.maND, hotenND
FROM NGUOI_DUNG AS ND, CUA_HANG AS CH
WHERE ND.maND = CH.maCCH AND hotenND LIKE '%Thái%'
ORDER BY tenCH ASC;
-- hiển thị danh sách các cửa hàng (mã, tên, trạng thái CH) mà nhân viên có mã 23456781 đang làm việc.
SELECT maCH, tenCH, diachiCH
FROM LAM_VIEC_TAI AS LV, CUA_HANG AS CH
WHERE LV.maCH = CH.maCH AND LV.maND = '23456781'
ORDER BY tenCH ASC;

-- hiển thị danh sách các cửa hàng (mã, tên, trạng thái)
-- và số lượng nhân viên đang làm việc tại cửa hàng theo thứ tự số lượng từ bé đến lớn
SELECT maCH, tenCH, diachiCH, COUNT(*)
FROM LAM_VIEC_TAI AS LV, CUA_HANG AS CH
WHERE LV.maCH = CH.maCH
ORDER BY COUNT(*) ASC;

-- hiển thị các cửa hàng mà có số lượng nhân viên nhiều hơn 2 người theo thứ tự từ lớn đến bé
SELECT maCH, tenCH, COUNT(*)
FROM LAM_VIEC_TAI AS LV, CUA_HANG AS CH
WHERE LV.maCH = CH.maCH
GROUP BY maCH HAVING COUNT(*) > 2
ORDER BY COUNT(*) ASC;

-- hiển thị các cửa hàng mà có nhiều hơn 3 đơn hàng, xếp theo thứ tự số lượng đơn hàng từ bé đến lớn
SELECT maCH, tenCH, COUNT(*)
FROM CUA_HANG, DON_HANG
WHERE CUA_HANG.maCH = DON_HANG.maCH
GROUP BY CUA_HANG.maCH HAVING COUNT(*) > 3
ORDER BY COUNT(*) ASC;

-- yeu cầu số 2
-- thủ tục hiển thị danh sách các cửa hàng của người dùng có mã là 12345678

DELIMITER //
CREATE PROCEDURE DanhSachCH (IN ma_chu_cua_hang INT)
BEGIN 
	IF (ma_chu_cua_hang < 10000000 OR ma_chu_cua_hang > 99999999) THEN
		SELECT CONCAT('Chủ cửa hàng', CAST(ma_chu_cua_hang AS CHAR), 'không tồn tại!');
	ELSE
		SELECT maCH, tenCH,trangthaiCH
		FROM CUA_HANG
		WHERE maCCH = ma_chu_cua_hang;
END //
DELIMITER ;

CALL DanhSachCH(12345678);

--thay đổi trạng thái của cửa hàng 
DELIMITER//
CREATE PROCEDURE ThayDoiTrangThai (IN ma_cua_hang INT)
BEGIN
	IF (SELECT trangthaiCH
		FROM CUA_HANG
		WHERE maCH = ma_cua_hang) = 1 THEN
		SELECT('Cửa hàng đã được kích hoạt')
	ELSE
		UPDATE CUA_HANG
		SET trangthaiCH = 1
		WHERE maCH = ma_cua_hang;
		END IF;
	END //
-- trigger
-- trigger tăng số lượng cửa hàng khi tăng thêm 1 chủ cửa hàng
