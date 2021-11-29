using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;

public class YeuCau : MonoBehaviour
{
    public Text stt;
    public Text id;
    public Text trangthai;
    public Text maVanDon;
    public Text ngayTao;
    public Text noiDung;
    public GameObject panelBtn;

    public void Xoa()
    {
        QuanLyYeuCau.instance.Xoa(this);
    }

    public void ChinhSua()
    {
        QuanLyYeuCau.instance.formChinhSua.SetActive(true);
        QuanLyYeuCau.instance.yeuCauEdit = this;
        QuanLyYeuCau.instance.maYeuCau.text = id.text;
    }
}
