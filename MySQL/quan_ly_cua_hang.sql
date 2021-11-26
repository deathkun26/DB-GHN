-- yeu cau so 1
-- a.	2 Câu truy vấn từ 2 bảng trở lên có mệnh đề where, order by
-- b.	2 Câu truy vấn có aggreate function, group by, having, where và order by có liên kết từ 2 bảng trở lên


-- hiển thị danh sách tất cả các cửa hàng của người dùng theo thứ tự từ cũ đến mới
SELECT maCH, tenCH, trangthaiCH, sodienthoaiCH, diachiCH
FROM CUA_HANG, CHU_CUA_HANG
WHERE maCCH = maND
ORDER BY maCH;
-- hiển thị