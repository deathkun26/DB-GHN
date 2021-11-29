using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
public class SanPhamMoi : MonoBehaviour
{
    public InputField tenInput;
    public InputField klInput;
    public InputField slInput;
    public string tenSanPham;
    public int khoiLuong;
    public int soLuong;

    public void CapNhatTen()
    {
        tenSanPham = tenInput.text;
    }
    public void CapNhatKhoiLuong()
    {
        if (klInput.text == "")
            klInput.text = "0";
        khoiLuong = int.Parse(klInput.text);
        if (khoiLuong < 0)
        {
            khoiLuong = 0;
            klInput.text = "0";
        }

        LenDonHang.instance.CapNhatKhoiLuong();
    }

    public void CapNhatSoLuong()
    {
        if (slInput.text == "")
            slInput.text = "0";
        soLuong = int.Parse(slInput.text);
        if (soLuong < 0)
        {
            soLuong = 0;
            slInput.text = "0";
        }

        LenDonHang.instance.CapNhatKhoiLuong();
    }

    public void Huy()
    {
        LenDonHang.instance.XoaSanPham(gameObject);
    }
}
