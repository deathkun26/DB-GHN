using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;

public class NhanVien : MonoBehaviour
{
    public Text stt;
    public Text id;
    public Text ten;
    public Text sdt;
    public Text cuahang;

    public void Xoa()
    {
        QuanLyNhanVien.instance.XoaNhanVien(this);
    }

}
