using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;
public class DonHang : MonoBehaviour
{
    public Text stt;
    public Text maVanDon;
    public Text nguoiNhan;
    public Text sdt_DiaChi;
    public Text trangThai;
    public Text phiDichVu;

    #region Chức năng
    public GameObject chinhSuaBtn;
    public GameObject traCuuBtn;
    public GameObject xoaBtn;
    #endregion

    public void Display()
    {
        if (trangThai.text == "Đơn nháp")
        {
        }
        else
        {
            xoaBtn.SetActive(false);
            chinhSuaBtn.SetActive(false);
        }
        traCuuBtn.SetActive(false);
    }

    // Todo: copy dữ liệu ở đơn nháp qua trang lên đơn hàng
    public void ChinhSua()
    {
        QuanLyDonHang.instance.lenDonHang.SetActive(true);
        //while (LenDonHang.instance == null) ;
        LenDonHang.instance.Load(maVanDon.text);
    }
    public void TraCuu()
    {
        //StartCoroutine(TraCuuRequestHandler());
    }

    public void Xoa()
    {
        StartCoroutine(XoaRequestHandler());
    }

    IEnumerator XoaRequestHandler()
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("delete_order", "");
        form.AddField("order_id", maVanDon.text);

        // * URL
        string url = "http://localhost/php/order.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                Debug.Log("Xoá thành công đơn hàng : " + maVanDon.text);
                QuanLyDonHang.instance.Load();
            }
            else // * Request Fail
            {
                Debug.Log("Xoá đơn hàng -> Error : " + result[0]);
            }
        }

    }

    IEnumerator TraCuuRequestHandler()
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("Mã Vận Đơn", maVanDon.text);

        // * URL
        string url = "http://localhost/demotest/register.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            string[] result = www.downloadHandler.text.Split(';');

            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length; i++)
                {


                }
                Debug.Log("Tra cứu thành công đơn hàng : " + maVanDon.text);
            }
            else // * Request Fail
            {
                Debug.Log("Tra cứu đơn hàng -> Error : " + result[0]);
            }
        }
    }
}
